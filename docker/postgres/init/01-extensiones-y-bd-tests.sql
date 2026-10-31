-- Se ejecuta solo la primera vez que se crea el volumen de datos.
-- Extensiones en la base de datos principal
CREATE EXTENSION IF NOT EXISTS postgis;
CREATE EXTENSION IF NOT EXISTS unaccent;

-- Base de datos para los tests (ADR-0006: los tests usan PostGIS, no SQLite)
CREATE DATABASE zbooks_testing;
\connect zbooks_testing
CREATE EXTENSION IF NOT EXISTS postgis;
CREATE EXTENSION IF NOT EXISTS unaccent;
