# DW Morgan Covid

## Getting Started

### Prerequisites
- PHP
- Composer
- Postgres

### Installing
1. git clone https://github.com/paulolorenzobasilio/events.git
2. cd into project dir
3. cp .env.example .env
4. configure your .env environment 
```
APP_KEY=SECRET_32_STRING_KEY
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=YOUR_DATABASE
DB_USERNAME=YOUR_USERNAME
DB_PASSWORD=YOUR_PASSWORD
```
5. composer install
6. php artisan migrate
7. php artisan db:seed

## Development
Run `php -S localhost:8000 -t public` for the server

## Usage
Run the server `php -S localhost:8000 -t public`

Access the endpoint **/top/confirmed**
`http://localhost:8000/top/confirmed?max_results=2&observation_date=2020-01-22`

Sample result
```
{
  "observation_date": "2020-01-22",
  "countries": [
    {
      "country": "Mainland China",
      "confirmed": 547,
      "deaths": 17,
      "recovered": 28
    },
    {
      "country": "Japan",
      "confirmed": 2,
      "deaths": 0,
      "recovered": 0
    }
  ]
}
```



