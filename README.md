# BandoCat
BandoCat is a web-based platform that provides a workspace for your team to catalog, store, transcribe, georectify, and publish maps and documents. 
For managers, BandoCat provides statistics, audit trails, issue ticket system, and user-level security. Together, all of these features make BandoCat 
the nice and friendly user-interface program that makes your operations and daily tasks easy to accomplish.
For more information about features of this application, please contact @snguyen1
_____
## Tech stack
- PHP7
- Imagemagick
- Python 2.7 + GDAL library
_____
## Development Environment Setup (Docker-Compose)
1. `docker-compose up`
2. Go to adminer interface at: `localhost:8080` and enter the credentials to login (server: `db`, username: `root`, password: `<MYSQL_ROOT_PASSWORD>` from `.env.dist`)
3. Once you are logged in, import all the databases including `bandocatdb` and the collection databases. Please reach out to @snguyen1 for the .sql files
4. Bandocat should be accessible via `localhost`

Have fun programming! :)

___
## Point of Contact:
Son Nguyen - son.nguyen@tamucc.edu