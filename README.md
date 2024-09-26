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
1. Clone the repository
2. Copy `.env.dist` to `.env` and update the environment variables
3. Move .sql database files to `init_db` folder in the root directory (Create the folder if it does not exist) - Reach out to @snguyen1 to get the database files
4. Run `docker-compose up`
5. Wait for the db to finish initializing
6. Bandocat should be accessible via `http://localhost`
7. PHPMyAdmin should be accessible via `http://localhost:8081` (server: `db`, username: `root`, password: See variable `MYSQL_ROOT_PASSWORD` in `.env` file)


Have fun programming! :)

___
## Point of Contact:
Son Nguyen - son.nguyen@tamucc.edu

## Issues: 
    - Mapbox will need to have an account that can be used for the TOKEN to have markers on georectification
    - Menu/Procedure&SupportSoftware restricted 
    -