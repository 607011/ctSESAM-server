# ctpwdgen-server

**Sync server for ctpwdgen**


## Data storage

All data the be synced with the clients is stored in an SQL database (SQLite 3).
The table layout is as follows:

Field  | Type | Description
------ | ---- | -----------
id     | INT  | primary key
userid | TEXT | associated user name
domain | TEXT | domain name
data   | BLOB | encrypted, then base64 encoded data


## Communication protocols

All data sent from the client to server **must** be sent via HTTP(S)-POST with MIME type "application/x-www-form-urlencoded". The request **must** contain a HTTP basic authorization header like `Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==` in which the last string portion resembles the base64 encoded concatenation of _username_:_password_.

The server extracts _username_ from the header and uses it to reference the table field `userid`, e.g. for selecting only entries of a certain user. _password_ is ignored and **can** be empty unless the header is actually used for HTTP basic authorization.

The server responds with JSON encoded data. 

The data set for a single database contains fields with exactly the same names as in the database, e.g.:

```
{
  'id': 12345,
  'userid': 'ola',
  'domain': 'ct.de',
  'data': 'GnsiY3QuZGUiOnsiYXZvaWRBbWJpZ3VvdXMiOnRyd...'
}
```


### Reading all data for a certain user

Access ajax/read.php to read all user's data.

The POST data **shall** be empty.

The reply contains an array of data sets in the field `result` (see above):

```
{
 'result': [
    { <data set 1> },
    { <data set 2> },
    { <data set 3> },
    ...
  ],
  'status': 'ok',
  'error': ''
}
```

The `status` field contains "ok" if no errors occured. Otherwise it contains "error" and the field `error` contains a message describing the error.

