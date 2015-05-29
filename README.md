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

## Encoding data

The `data` field contains base64 encoded AES encrypted JSON data.

The encoding process at a glance:

```
         to json            encryption                 base64 
raw data -------> JSON data ----------> encrypted data ------> base64 encoded data
```

Converting the raw data to the JSON object literal **must** lead to the following structure whereby `<string>`, `boolean` and so on describes the type of the actual value:

```
{
  'domain': <string>,
  'username': <string>,
  'useLowerCase': <boolean>,
  'useUpperCase': <boolean>,
  'useDigits': <boolean>,
  'useExtra': <boolean>,
  'useCustom': <boolean>,
  'avoidAmbiguous': <boolean>,
  'customCharacterSet': <string>,
  'iterations': <integer>,
  'length': <integer>,
  'salt': <salt>,
  'forceValidation': <boolean>
  'validatorRegEx': <string>,
  'cDate': <string>,
  'mDate': <string>
}
```

For encryption, the client **must** use AES in CBC mode (cipher block chaining) with a 256 bit key and the byte sequence `0xb5, 0x4f, 0xcf, 0xb0, 0x88, 0x09, 0x55, 0xe5, 0xbf, 0x79, 0xaf, 0x37, 0x71, 0x1c, 0x28, 0xb6` as the initialization vector (IV).

The resulting cipher **must** then be base64 encoded. The reason for this lies in the network communication protocol for which all data has to be url encoded (see below). If the data already has been base64 encoded the data to be transferred is smaller than if binary data has to be url encoded.



## Communication protocols

All data sent from the client to server **must** be sent via HTTP(S)-POST with MIME type "application/x-www-form-urlencoded". The request **must** contain a HTTP basic authorization header like `Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==` in which the last string portion resembles the base64 encoded concatenation of _username_:_password_.

The server extracts _username_ from the header and uses it to reference the table field `userid`, e.g. for selecting only entries of a certain user. _password_ is ignored and **can** be empty unless the header is actually used for HTTP basic authorization.

The server responds with JSON encoded data. 

When applicable, i.e. when the server is requested to respond with single or multiple data sets, a single data set for a single database contains fields with exactly the same names as in the database, e.g.:

```
{
  'id': 12345,
  'userid': 'ola',
  'domain': 'ct.de',
  'data': 'GnsiY3QuZGUiOnsiYXZvaWRBbWJpZ3VvdXMiOnRyd...'
}
```

Multiple data sets are sent as an array of data sets:

```
[
  { <data set 1> },
  { <data set 2> },
  { <data set 3> },
  ...
]
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


