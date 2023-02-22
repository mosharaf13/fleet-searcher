## Fleet Searcher

### Description

fleet-searcher is a web application that allows you to search google using a CSV file fo keywords and view stats in it.

### Requirements

- Docker and Docker Compose
- Composer

### Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/fleet-searcher.git
```

2. Navigate to the project directory:

```bash
cd fleet-searcher
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
8.Seed the database:
```bash
./vendor/bin/sail artisan db:seed
```

### Usage

Once the Docker containers are up and running, you can access the application in your web browser at http://localhost. From there, you can perform searches on google and view the results.

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
