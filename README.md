## Fleet Searcher

### Description

fleet-searcher is a web application that allows you to search google using a CSV file fo keywords and view stats in it.

### Requirements

- Docker and Docker Compose
- Composer
- PHP 8 with common extensions
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

4. Logout and log back in to the terminal.
5. Then Start the app running the following script

```bash
bash setup/start_app.sh
```

### Usage

Once the Docker containers are up and running, you can access the application in your web browser at http://localhost.
From there, you can perform searches on google and view the results.

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
.vendor/bin/sail test --coverage-html ./coverage
```

### License

The Fleet Searcher project is open-source software licensed under the MIT license.
