# 📦 Systex WMS 4.0  

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red?logo=laravel)](https://laravel.com/)  
[![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)](https://www.php.net/)  
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql)](https://www.mysql.com/)  
[![Node.js](https://img.shields.io/badge/Node.js-20-green?logo=node.js)](https://nodejs.org/)  
[![Composer](https://img.shields.io/badge/Composer-2.x-orange?logo=composer)](https://getcomposer.org/)  
[![License](https://img.shields.io/badge/license-Systex%20Proprietary-lightgrey)](#-licença)  

Sistema de **Gestão de Armazéns (WMS)** desenvolvido em **Laravel 11** e **MySQL**, projetado para operações logísticas complexas.  
O WMS 4.0 cobre todos os processos logísticos, incluindo **recebimento, armazenagem, separação, expedição, inventário, contagem de paletes e kits**, oferecendo painéis de produtividade e relatórios avançados.  

---

## 🚀 Tecnologias Utilizadas
- [Laravel 11](https://laravel.com/)  
- [PHP 8.2+](https://www.php.net/)  
- [MySQL 8](https://dev.mysql.com/)  
- [Composer](https://getcomposer.org/)  
- [Node.js + Vite](https://vitejs.dev/)  
- [TailwindCSS / Template Hyper](https://tailwindcss.com/)  

---

## 📂 Estrutura de Pastas

/app
/Http/Controllers # Lógica dos módulos
/Models # Modelos do Eloquent
/Services # Regras de negócio
/config # Configurações
/database # Migrations e seeds
/public # Ponto de entrada e assets
/resources/views # Views Blade (Hyper)
/routes # Rotas web/api

🛠️ Roadmap

 Módulo de frota integrado ao WMS

 Integração com Power BI

 App Mobile (Flutter)

 Multi-tenant SaaS

 
---

## ⚙️ Funcionalidades
✅ Gestão de Recebimento (com conferência e divergências)  
✅ Gestão de Armazenagem e Separação  
✅ Controle de Estoque e Inventário  
✅ Expedição e Relatórios de Saída  
✅ Contagem de Paletes e Kits  
✅ Relatórios Gerenciais com gráficos (produtividade, comparativos, etc.)  
✅ Multiusuário com permissões por setor  
✅ Compatível com coletores de dados (PWA offline/online)  

---

## 📊 Dashboard
- Gráficos de produtividade por setor  
- Indicadores de separação, armazenagem e expedição  
- KPIs em tempo real  

---

## 🔧 Instalação

### Pré-requisitos
- PHP 8.2+
- Composer
- MySQL 8
- Node.js (com NPM ou Yarn)

### Passos
```bash
# Clonar o repositório
git clone https://github.com/systex/wms4.0.git

# Entrar no diretório
cd wms4.0

# Instalar dependências do Laravel
composer install

# Instalar dependências do front-end
npm install && npm run build

# Criar arquivo de configuração
cp .env.example .env

# Gerar key da aplicação
php artisan key:generate

# Configurar banco e rodar migrations
php artisan migrate --seed

# Subir servidor local
php artisan serve


📜 Licença

Este projeto é de uso interno da Systex Sistemas Inteligentes.
Não é permitido uso comercial sem autorização.

👨‍💻 Autor

Systex Sistemas Inteligentes
🌐 systex.com.br

📧 manoel.filho@systex.com.br
