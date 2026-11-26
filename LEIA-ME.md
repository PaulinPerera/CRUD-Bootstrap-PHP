# QuadriVerse – CRUD Bootstrap PHP

## Instalação (XAMPP/WAMP)
1. Coloque a pasta em `htdocs` com o nome **CRUD-Bootstrap-PHP** (é o `BASEURL` do `config.php`).
2. Importe `tabelarevista.sql` no MySQL (cria o banco `banco`, as tabelas e os dados de exemplo).
3. Confira usuário/senha do MySQL no `config.php`.
4. Dê permissão de escrita na pasta `img/` (é para onde vão as capas e fotos enviadas).
5. Abra `http://localhost/CRUD-Bootstrap-PHP/`

## Login inicial
- Usuário: `admin`
- Senha: `admin123`  → **troque em Usuários > Editar** logo no primeiro acesso.

## Quem acessa o quê
| Área | Visitante | Logado | Admin |
|---|---|---|---|
| Ver revistas | sim | sim | sim |
| Cadastrar / editar / excluir revistas | não | sim | sim |
| Clientes | não | sim | sim |
| Usuários | não | não | sim |
