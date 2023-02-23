## Fleet Searcher

### Description

fleet-searcher is a web application that allows you to search google using a CSV file fo keywords and view stats in it.

### Requirements

- Docker and Docker Compose
- Composer
- PHP 8 with the following extensions: pdo, pdo_mysql, mbstring, tokenizer, xml, curl
- Node.js and npm

### Installation

1. Clone the repository:

```bash
git clone git@github.com:mosharaf13/fleet-searcher.git fleet-searcher
```

2. Navigate to the project directory:

```bash
cd fleet-searcher
```

3. If you don't have the dependencies installed. Run the following command
```bash
bash setup/install_dependencies.sh
```

3. Copy the .env.example file to .env:

```bash
cp .env.example .env
```

4. Install the dependencies using Composer:

```bash
composer install
```

5. Start the Docker containers using Laravel Sail:
```bash
./vendor/bin/sail up
```

6. Generate an application key:
```bash
./vendor/bin/sail artisan key:generate
```
7. Migrate the database:
```bash
./vendor/bin/sail artisan migrate
```

### Usage

Once the Docker containers are up and running, you can access the application in your web browser at http://localhost. From there, you can perform searches on google and view the results.

```bash
The default port for this application is 80. If you want to start the application on another port set  APP_PORT="YOUR PORT" in .env
```
### Testing

You can run the automated tests for the application using PHPUnit. To run the tests, use the following command:

```bash
./vendor/bin/sail test
```

If you want generate code coverage then run

```bash
vendor/bin/sail test --coverage-html ./coverage
```

### License

The Fleet Searcher project is open-source software licensed under the MIT license.
