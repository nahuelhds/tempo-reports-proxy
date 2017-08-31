# README #

This project works as a proxy in to order to be able to access to the Tempo Timesheets reports.
This main goal was [to be able to do this](http://blog.tempo.io/2012/creating-excel-reports-using-high-level-permissions-api-export/) from Google Spreadsheet within our own JIRA & Tempo server.

## Installation

In any public accesible folder in your server, install the project and initialize it:

```sh
git clone https://nsotelo@bitbucket.org/cristalmedia/reporte.git ./myReports
cd myReports
composer install
```

## Security concerns

The file doesn't care about who's accessing it, so I recommend to implement a minimal access security (e.g.: a token).

## Usage

From an external program -like Google SpreadSheet- use this URL as a data source
Desde un programa externo, apuntar a la URL:

```
https://myownserver.com/myReports/?from=Y-m-d&to=Y-m-d
```

As you can see, the URL has two optional arguments: `from` and `to`.
* The format for both of them is **yyyy-mm-dd**.
* If `from` is undefined *the first day of the month* is taken.
* Similarly, if `to` is absent, *the curren day* is set for it.
