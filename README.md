# link shortener

This lives at link.cckitchen.uk

1. link.cckitchen.uk displays a read-only version of the "allthedocs"
   spreadsheet (specifically, a locked, public, read-only copy of the sheet)
2. when someone loads the page, if the local server "links.csv" is more than an
   hour old, it grabs that sheet, and stores it as an updated csv (there's also
   a backup of yesterdays csv in backup_from_yesterday/links.csv in case
   something goes wrong)
3. valid short-urls will redirect the user immediately to that resource. E.g.
   link.cckitchen.uk/donate redirects to the opencollective page.

## local dev

you can either copy "example-links.csv" to "links.csv", or, running the
application will create one for you
