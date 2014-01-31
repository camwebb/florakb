# Speficications (phase 1: up to workshop in May 2014)

## General structure

 * Bulk upload
    * Template
    * XLS parser to tables-as-PHP-memory-arrays
    * Validator
       * Reads `bulk_valid.php`
       * Checks data
       * **Provides user feedback**
    * Upload to DB(s) inside transaction (`SET AUTOCOMMIT = 0; START 
      TRANSACTION; .... COMMIT;`)
 * One-by-one user interface
 * Database1 - scientific and user data
    * Schema (must be evolvable)
 * Database2 - internal tables
 
