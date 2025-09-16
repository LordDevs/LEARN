# App Help Desk

Modernização do projeto `Projetos-xamp/Projeto_helpdesk`, com melhorias de segurança, experiência do usuário e entrega em container.

## Principais recursos

- Interface responsiva atualizada para Bootstrap 5, com validações de formulário no navegador.
- Proteção CSRF, sanitização de dados e senhas armazenadas com `password_hash`.
- API JSON (`api/tickets.php`) para criar e listar chamados via `fetch`, abastecendo páginas dinâmicas.
- Persistência simples em arquivo (`storage/arquivo.hd`) com filtros por perfil de usuário.

## Executando com Docker

```bash
# build + run
docker-compose up --build

# acessar
http://localhost:8080
```

O volume `./Projetos-xamp/Projeto_helpdesk/storage` é montado no container para manter os chamados salvos entre execuções.

## Credenciais padrão

| E-mail               | Senha | Perfil          |
|---------------------|-------|-----------------|
| `adm@teste.com.br`  | 1234  | Administrativo |
| `user@teste.com.br` | 1234  | Administrativo |
| `jose@teste.com.br` | 1234  | Usuário         |
| `maria@teste.com.br`| 1234  | Usuário         |

Após autenticar, é possível abrir chamados pela página "Abrir chamado" e acompanhá-los em tempo real em "Consultar chamados".
