Laravel procedure migration generator

This package will generate migrations from any procedures that currently remain in your database.

It will present a complete list of all procedures available from any database it can find.  
You can then select any individual procedure for which you want a migration to be written.

It will not overwrite any existing migrations, instead it will prompt you with a list incase a procedure already exists.
You can manually delete the procedure and run the "migrations" again to make a new migration for that specific procedure.