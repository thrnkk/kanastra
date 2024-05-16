1. Copie o repositório com `git clone https://github.com/thrnkk/kanastra.git`
2. Vá até a raíz do repositório com `cd kanastra`
3. Dentro de `backend` crie um aquivo chamado `.env` baseado no arquivo `.env.example`
4. A partir da pasta raíz, execute o comando `docker compose up --build` (essa etapa tende a demorar alguns minutos para baixar todas as dependências)

Para rodar os testes é possível utilizar o comando `docker exec -it {nome do container} php artisan test`

Após a conclusão da etapa 4, é possível utilizar o sistema acessando `http://localhost:5173/`.
