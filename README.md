<h2>A REST interface between PHP and MongoDB</h2>

This layer was create with the intention of retrieving and including data, it does not have the hability do create databases or collections. 

<h4>Required steps to make it work:</h4>
 - configure <b>user</b>, <b>password</b> and <b>host</b> on <b>/Mongo/MongoConf.php</b>

---------

<h3>AVALIABLE ACTIONS</h3>

<h4>GET /</h4>
<code><b>Response:</b> HTTP/1.0 400</code><br/>
<code><b>Message:</b> <i>Provide me a DB and a Collection</i></code>

<h4>GET /db</h4>
<code><b>Response:</b> HTTP/1.0 400</code><br/>
<code><b>Message:</b> <i>Provide me a Collection</i></code>

<h4>GET /db/collection</h4>
List all the Object of that Collection
<code><b>Response:</b> HTTP/1.0 200</code><br/>
<code><b>Structure:</b> [ {object} ]</code>

<h4>POST /db/collection</h4>
Create an Object
<code><b>Request:</b> Object information</code><br/>
<code><b>Response:</b> HTTP/1.0 200</code><br/>
<code><b>Structure:</b> [ {object} ]</code>

<h4>GET /db/collection/id</h4>
List one item based on the MongoID informed
<code><b>Response:</b> HTTP/1.0 200</code><br/>
<code><b>Structure:</b> {object} </code>

<h4>PUT /db/collection/id</h4>
Replace data completely based on the Mongo ID informed
<code><b>Request:</b> MongoID and Object information</code><br/>
<code><b>Response:</b> HTTP/1.0 200</code><br/>
<code><b>Structure:</b> [ {object} ]</code>

<h4>Path /db/collection/id</h4>
Replace part of the Object based on the information sent
<code><b>Request:</b> MongoID and Object information</code><br/>
<code><b>Response:</b> HTTP/1.0 200</code><br/>
<code><b>Structure:</b> [ {object} ]</code>

<h4>Path /db/collection/id</h4>
Delete one Object based on the Mongo ID informed
<code><b>Request:</b> MongoID</code><br/>
<code><b>Response:</b> HTTP/1.0 200</code><br/>
