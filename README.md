# Instalando

## Requisitos

- [ ] Laravel Sail
- [ ] Docker
- [ ] Docker compose

## Rodando a aplicação

```bash
./sail up -d
./sail composer install
./sail artisan migrate
./sail artisan queue:work
```

A aplicação deverá ficar disponível na porta 8000
