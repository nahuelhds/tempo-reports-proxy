# README #

Este proyecto funciona como proxy con el servidor JIRA & Tempo, de modo tal de poder obtener la exportación de registros de forma automatizada.

## Instalación

Para dejar el proyecto funcionando, basta con realizar los siguientes comandos:

```sh
git clone https://nsotelo@bitbucket.org/cristalmedia/reporte.git ./reporteCSV
cd reporteCsv
composer install
```

## Importante
El proyecto debe estar en una carpeta públicamente accesible.

## Caso de uso

Desde un programa externo, apuntar a la URL:

```
https://miservidor.com/reporte/?from=Y-md&to=Y-m-d
```

Como se puede apreciar, el programa espera dos parámetros GET, ambos opcionales: `from` y `to`.
* El formato esperado de ambos es **yyyy-mm-dd**.
* Si `from` no está definido, se toma automáticamente el *primer día del mes*.
* De modo similar, ante la ausencia de `to` se toma la *fecha de hoy*.

