# Bank App
Simple exercise of a bank app that takes a CSV file of deposits/withdrawals and generates commission fees

## Getting Started

### Dependencies

* ```PHP >=7.0```
* ```Composer 2.2.0```
* ```ext-bcmath *```
* ```PHPUnit```

### Installing

* Step 1: ```composer update```
* Step 2: ```composer install```

### Executing program


* Running the main script

```
php script.php /path/to/file.csv
```
This will read the given CSV file and output the calculated commission fees

<br />

* Run script with preset currency exchange rates
```
php script.php /path/to/file.csv --use-preset-rates
```

This option will tell the script to not call the currency exchange API and simply use these preset values:
```
EUR:USD - 1:1.1497
EUR:JPY - 1:129.53
```

* Example of the CSV file format
```
2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
2016-01-05,1,private,deposit,200.00,EUR
2016-01-06,2,business,withdraw,300.00,EUR
2016-01-06,1,private,withdraw,30000,JPY
2016-01-07,1,private,withdraw,1000.00,EUR
2016-01-07,1,private,withdraw,100.00,USD
2016-01-10,1,private,withdraw,100.00,EUR
2016-01-10,2,business,deposit,10000.00,EUR
2016-01-10,3,private,withdraw,1000.00,EUR
2016-02-15,1,private,withdraw,300.00,EUR
2016-02-19,5,private,withdraw,3000000,JPY
```

* Running tests
```
composer run-script phpunit
```