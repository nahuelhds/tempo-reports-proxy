# README #

This project works as a proxy in to order to be able to access to the Tempo Timesheets reports.
This main goal was [to be able to do this](http://blog.tempo.io/2012/creating-excel-reports-using-high-level-permissions-api-export/) from within Google Spreadsheet.

## Installation

In any public accesible folder in your server, install the project and initialize it:

```sh
git clone https://github.com/nahuelhds/tempo-reports-proxy.git ./myReports
cd myReports
composer install
```

## Security concerns

The file doesn't care about who's accessing it, so I recommend to implement a minimal access security (e.g.: a token).

## Usage

### Configuring your credentials

1. You need to enable your server IP on Tempo Access Control
1. Obtain your Tempo Api Key (security token).
1. Then, define your server url (usually something like `http(s)://yourserver.yourdomain/plugins/servlet/tempo-getWorklog`)

All that is explained in [this Tempo post](http://blog.tempo.io/2012/creating-excel-reports-using-high-level-permissions-api-export/).
Once you get your server URI and your Tempo Api Token you need to replace them in the `./index.php` file, lines 4 and 5.

### Accessing the data from outside

From an external program -like Google SpreadSheet- use this URL as a data source
Desde un programa externo, apuntar a la URL:

```
https://myownserver.com/myReports/?from=Y-m-d&to=Y-m-d
```

As you can see, the URL has two optional arguments: `from` and `to`.
* The format for both of them is **yyyy-mm-dd**.
* If `from` is undefined *the first day of the month* is taken.
* Similarly, if `to` is absent, *the curren day* is set for it.
