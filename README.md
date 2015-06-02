# ctpwdgen-server

**Sync server for ctpwdgen**

_ctpwdgen-server_ is a data store for [ctpwdgen](https://github.com/ola-ct/ctpwdgen). It's supposed to be installed on a web server and used by only you or a small community (your family, close friends).

The web server **must** support PHP 5.x (or newer) with SQLite module activated.

## Getting Started

Your personal instance of _ctpwdgen-server_ is easy to set up:

 * Clone the [repository](https://github.com/ola-ct/ctpwdgen-server).
 * Copy the project files into a directory accessible by your webserver. The following assumes that all files are contained in the directory D:/Developer/xampp/htdocs/ctpwdgen-server and that this directory can be accessed by a client via the URL https://localhost/ctpwdgen-server. Reminder: You **should** prefer to use HTTPS instead of unencrypted HTTP because _ctpwdgen-server_ authorizes its users via [HTTP basic access authentication](http://en.wikipedia.org/wiki/Basic_access_authentication) by which credentials are transferred in an unsafe way. HTTPS requires the setup of [certificate chain](https://github.com/ola-ct/ctpwdgen-server/wiki/Creating%20CA%20Signed%20Certificates%20For%20Your%20Webserver).
 * For HTTP basic access authentication you **should** protect the just created directory by placing an .htaccess file there (Apache users!) or doing something else adequate for your preferred web server.
 * Check that your configuration by calling the URL in your favorite web browser: You should see the message "This page is intentionally left blank.".
 * _ctpwdgen-server_ uses an SQLite 3 database to store data. The path to the database is configured in the project file ajax/config.php. Set the variable `$DB_PATH` accordingly. By default it's D:/Developer/xampp. The configured directory **must** be writable by your web server.
 * Call https://localhost/ctpwdgen-server/ajax/install.php to create the database.

_ctpwdgen-server_ is now ready for action.

## Data Storage

The SQLite 3 database contains a single table with the following layout:

Field   | Type | Description
------- | ---- | -----------
userid  | TEXT | associated user name (primary key)
data    | BLOB | encrypted, then base64 encoded ctpwdgen parameters

## Encoding data

The `data` field contains base64 encoded AES encrypted JSON data.

The encoding process at a glance:

```
         to json            compress                  encryption                 base64 
raw data -------> JSON data --------> compressed data ----------> encrypted data ------> base64 encoded data
```

Decoding works vice versa.


### Data Specs

Converting the raw data to the JSON object literal **must** lead to the following structure whereby `<string>`, `boolean` and so on describe the types of the actual values:

```
{
  'domain': <string>,
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
  'mDate': <string>,
  'deleted' <boolean>
}
```

The field `domain` contains the domain the rest of the data refers to.

If `useLowerCase` is `true`, lower case characters ("abcdefghijklmnopqrstuvwxyz") are added to the pool of characters available for password generation.

If `useUpperCase` is `true`, all upper case characters ("ABCDEFGHIJKLMNOPQRSTUVWXYZ") are added to the pool of characters available for password generation. If `avoidAmbiguous` is `true` upper case characters except "I" are added.

If `useDigits` is `true`, all digits ("0123456789") are added to the pool of characters available for password generation.

If `useDigits` is `true`, a couple of special characters ("#!\"§$%&/()[]{}=-_+*<>;:.") are added to the pool of characters available for password generation.

If `forceValidation` is `true` the resulting password **must** match the regular expression given in `validatorRegEx`.

The field `iterations` contains the number of iterations used for [PBKDF2](http://en.wikipedia.org/wiki/PBKDF2).

The field `length` contains the required length of the generated password.

The field `salt` contains the salt use for [PBKDF2](http://en.wikipedia.org/wiki/PBKDF2).

The fields `cDate` and `mDate` contain dates in [ISO 8601 format](http://en.wikipedia.org/wiki/ISO_8601) (e.g. "2015-05-28T14:07:12"), whereby `cDate` resembles the date and time when the data set was created, and `mDate` when it was last modified.

If `deleted` is `true`, the client **should** consider this entry as deleted. It's up to client how to interpret this flag. The client **may** reset this flag.

### Compression

The data is compressed using the [DEFLATE](http://en.wikipedia.org/wiki/DEFLATE) algorithm used by [zlib](http://en.wikipedia.org/wiki/Zlib).

The compressed data is prepended by a four byte long header. The header contains the expected length (in bytes) of the uncompressed data, expressed as an unsigned, big-endian, 32-bit integer.


### Encryption

For encryption, the client **must** use AES in CBC mode (cipher block chaining) with a 256 bit key and the byte sequence `0xb5, 0x4f, 0xcf, 0xb0, 0x88, 0x09, 0x55, 0xe5, 0xbf, 0x79, 0xaf, 0x37, 0x71, 0x1c, 0x28, 0xb6` as the initialization vector (IV).

The resulting cipher **must** then be base64 encoded. The reason for this lies in the network communication protocol for which all data has to be url encoded (see below). If the data already has been base64 encoded the data to be transferred is smaller than if binary data has to be url encoded.

## Communication Protocols

### In General

All data sent from the client to server **must** be sent via HTTP(S)-POST with MIME type "application/x-www-form-urlencoded". The request **must** contain a HTTP basic authorization header like `Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==` in which the last string portion resembles the base64 encoded concatenation of _username_:_password_.

The server extracts _username_ from the header and uses it to reference the table field `userid`, e.g. for selecting only entries of a certain user. _password_ is ignored and **can** be empty unless the header is actually used for HTTP basic authorization.

The server responds with JSON encoded data. 

The data set for a single database contains fields with exactly the same names as in the database, e.g.:

```
{
  'userid': 'ola',
  'data': 'LGxVWtGUi7Cp/QlvACjbd9KBDcxft+ofxo...'
}
```

### Read All Domain Data

Access ajax/read.php to read all user's data.

The POST data **shall** be empty.

The reply contains an array of all domain data belonging to the user. The data is held by the field `result` (see above):

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

### Create/Update Domain Data

Access ajax/write.php to create a new data set for a user or updates an existing one.

The POST data **must** contain the following x-www-form-urlencoded field:

Field  | Contents
------ | --------------------------------------------
data   | the data to be stored in the field `data`.

If the server finds an entry for the given user, it updates the stored data with the contents of `data`. If not, a new entry is created.
