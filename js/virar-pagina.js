/**
 * Virar página: troca de página com animação de folha de papel (estilo "papel solto").
 * Intercepta links internos, busca a próxima página com fetch e vira a PÁGINA INTEIRA
 * (navbar + conteúdo + rodapé + fundo), ocupando toda a janela.
 * Se algo falhar, cai na navegação normal do navegador.
 */
(function () {
  'use strict';

  var MAIN = '#conteudo';
  var N = 22;                 // número de tiras (quanto maior, mais suave a curva)
  var K = 95;                 // curvatura máxima (graus)
  var DUR = 1900;             // duração (ms)
  var LIFT = 9;               // inclinação da folha (graus)
  var busy = false;

  // Coloque true para desligar a animação de quem ativou "reduzir movimento" no sistema.
  // Fica false para a animação funcionar mesmo com as animações do Windows desativadas.
  var RESPEITAR_REDUCAO_DE_MOVIMENTO = false;

  var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  if ((RESPEITAR_REDUCAO_DE_MOVIMENTO && reduce) || !window.fetch || !window.DOMParser) return;

  function ease(t) { return -(Math.cos(Math.PI * t) - 1) / 2; }

  function isSoftLink(a, e) {
    if (!a || e.defaultPrevented || e.button !== 0) return false;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return false;
    if (a.target && a.target !== '_self') return false;
    if (a.hasAttribute('download') || a.hasAttribute('data-bs-toggle')) return false;
    var href = a.getAttribute('href');
    if (!href || href.charAt(0) === '#' || /^(javascript|mailto|tel):/i.test(href)) return false;
    if (a.origin !== location.origin) return false;
    if (/\/(delete|logout|valida)\.php/i.test(a.pathname)) return false;
    if (a.pathname === location.pathname && a.search === location.search) return false;
    return true;
  }

  function closeMenus() {
    document.querySelectorAll('.dropdown-menu.show').forEach(function (m) {
      m.classList.remove('show');
      var t = m.previousElementSibling;
      if (t) { t.classList.remove('show'); t.setAttribute('aria-expanded', 'false'); }
    });
    var nav = document.getElementById('navbarCrud');
    if (nav && nav.classList.contains('show') && window.bootstrap) {
      bootstrap.Collapse.getOrCreateInstance(nav).hide();
    }
  }

  // Congela o estado de menus abertos no clone (evita pegar a animação do collapse/dropdown no meio)
  function limpaEstadoMenus(root) {
    root.querySelectorAll('.dropdown-menu.show, .dropdown-toggle.show').forEach(function (n) { n.classList.remove('show'); });
    root.querySelectorAll('.navbar-collapse').forEach(function (n) {
      n.classList.remove('show', 'collapsing');
      n.classList.add('collapse');
      n.style.height = '';
    });
  }

  /**
   * Monta um "retrato" da página inteira (tudo que está dentro do <body>), do tamanho da janela.
   * mainSrc: <main> a usar no retrato (null = o <main> que está na tela agora).
   * scrollY: quanto a página está rolada (para o retrato ficar igual ao que o usuário vê).
   */
  function buildPage(W, H, mainSrc, scrollY) {
    var page = document.createElement('div');
    page.className = 'vp-page';
    page.style.width = W + 'px';
    page.style.minHeight = H + 'px';
    page.style.top = (-scrollY) + 'px';

    Array.prototype.forEach.call(document.body.children, function (n) {
      if (/^(SCRIPT|STYLE|NOSCRIPT)$/.test(n.tagName)) return;
      if (n.classList.contains('vp-overlay') || n.classList.contains('vp-under-shadow')) return;
      if (n.classList.contains('modal-backdrop')) return;
      page.appendChild(n.cloneNode(true));
    });

    var m = page.querySelector(MAIN);
    if (m && mainSrc) { m.innerHTML = mainSrc.innerHTML; m.className = mainSrc.className; }

    limpaEstadoMenus(page);
    // a navbar é sticky: no retrato ela precisa ficar colada no topo da janela
    page.querySelectorAll('.sticky-top').forEach(function (n) {
      n.style.position = 'relative'; n.style.top = scrollY + 'px';
    });

    page.setAttribute('aria-hidden', 'true');
    page.setAttribute('inert', '');
    return page;
  }

  function buildLeaf(overlay, pageEl, W, H) {
    var w = W / N, strips = [], shades = [], parent = overlay;
    var fundo = getComputedStyle(document.body);
    for (var i = 0; i < N; i++) {
      var s = document.createElement('div');
      s.className = 'vp-strip';
      s.style.width = (w + 1) + 'px';
      if (i > 0) s.style.left = w + 'px'; else s.style.zIndex = 5;

      var f = document.createElement('div'); f.className = 'vp-face';
      var inner = document.createElement('div'); inner.className = 'vp-inner';
      inner.style.width = W + 'px'; inner.style.marginLeft = (-i * w) + 'px';
      // o fundo (imagem de quadrinhos) também vira junto com a folha
      inner.style.backgroundColor = fundo.backgroundColor;
      inner.style.backgroundImage = fundo.backgroundImage;
      inner.style.backgroundSize = 'cover';
      inner.style.backgroundPosition = 'center';
      inner.style.backgroundRepeat = 'no-repeat';
      var c = pageEl.cloneNode(true);
      inner.appendChild(c);
      var sh1 = document.createElement('div'); sh1.className = 'vp-shade';
      f.appendChild(inner); f.appendChild(sh1);

      var b = document.createElement('div'); b.className = 'vp-face vp-back';
      var sh2 = document.createElement('div'); sh2.className = 'vp-shade';
      b.appendChild(sh2);

      s.appendChild(f); s.appendChild(b); parent.appendChild(s);
      strips.push(s); shades.push(sh1, sh2);
      parent = s;
    }
    return { strips: strips, shades: shades };
  }

  function swapIn(main, doc, url, push) {
    var nm = doc.querySelector(MAIN);
    main.innerHTML = nm.innerHTML;
    main.className = nm.className;
    document.title = doc.title || document.title;
    if (push) history.pushState({ vp: 1 }, '', url);
  }

  function turn(url, push, fwd) {
    if (busy) return;
    busy = true;
    closeMenus();

    fetch(url, { credentials: 'same-origin' }).then(function (r) {
      if (!r.ok || new URL(r.url).origin !== location.origin) throw new Error('bad');
      return r.text().then(function (t) { return { text: t, url: r.url }; });
    }).then(function (res) {
      var doc = new DOMParser().parseFromString(res.text, 'text/html');
      var main = document.querySelector(MAIN);
      if (!main || !doc.querySelector(MAIN)) throw new Error('no main');

      // a folha ocupa a janela inteira (largura sem a barra de rolagem)
      var W = document.documentElement.clientWidth;
      var H = window.innerHeight;
      var scrollY = window.pageYOffset || 0;

      // página que vira: a atual (ao avançar) ou a nova (ao voltar)
      var leafPage = fwd ? buildPage(W, H, null, scrollY)
                         : buildPage(W, H, doc.querySelector(MAIN), 0);

      var overlay = document.createElement('div');
      overlay.className = 'vp-overlay';
      overlay.style.width = W + 'px'; overlay.style.height = H + 'px';
      overlay.style.perspective = Math.max(2400, W * 2.4) + 'px';
      overlay.style.perspectiveOrigin = '50% 40%';

      var under = document.createElement('div');
      under.className = 'vp-under-shadow';
      under.style.width = W + 'px'; under.style.height = H + 'px';

      // a nova página fica por baixo da folha; a folha cobre a janela inteira, então
      // o pulo de rolagem para o topo não aparece
      if (fwd) { swapIn(main, doc, res.url, push); window.scrollTo(0, 0); }

      document.body.appendChild(under);
      document.body.appendChild(overlay);
      var L = buildLeaf(overlay, leafPage, W, H);

      document.documentElement.classList.add('vp-turning');
      var liftK = Math.min(1, 700 / H);
      var t0 = performance.now();

      function frame(now) {
        var t = Math.min(1, (now - t0) / DUR);
        var s = fwd ? t : 1 - t;
        var theta = -180 * ease(s);
        var bend = K * Math.sin(Math.PI * s) * (1 - 0.5 * s);   // a borda livre adianta, como papel
        var d = -bend / N, a = theta;
        for (var i = 0; i < N; i++) {
          var st = L.strips[i];
          if (i === 0) {
            st.style.transform = 'rotateX(' + (LIFT * liftK * Math.sin(Math.PI * s)) + 'deg) rotateY(' + theta + 'deg)';
          } else {
            st.style.transform = 'rotateY(' + d + 'deg)';
            a += d;
          }
          var dark = 0.55 * (1 - Math.abs(Math.cos(a * Math.PI / 180)));
          L.shades[i * 2].style.opacity = dark;
          L.shades[i * 2 + 1].style.opacity = dark;
        }
        under.style.opacity = 0.75 * Math.sin(Math.PI * s);
        if (t < 1) { requestAnimationFrame(frame); return; }

        // ao voltar, a folha termina cobrindo a janela: troca o conteúdo por baixo dela
        if (!fwd) { swapIn(main, doc, res.url, push); window.scrollTo(0, 0); }
        overlay.remove(); under.remove();
        document.documentElement.classList.remove('vp-turning');
        busy = false;
      }
      requestAnimationFrame(frame);
    }).catch(function (err) {
      if (window.console) console.error('[virar-pagina] usando navegação normal:', err);
      busy = false;
      location.href = url;   // fallback: navegação normal
    });
  }

  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('a[href]');
    if (!isSoftLink(a, e)) return;
    e.preventDefault();
    turn(a.href, true, true);
  });

  history.replaceState({ vp: 1 }, '');
  window.addEventListener('popstate', function () {
    if (busy) { location.reload(); return; }
    turn(location.href, false, false);
  });
})();
