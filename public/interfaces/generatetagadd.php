

/* CONNECT A TAG TO THIS ENTITY START */

$tag = Tag::findFirst('entityid = "'.$post['id'].'"');
if(!$tag)
{
	$tag = new Tag();
	$tag->id = $this->uuid();
}
$tag->entityid = $post['id'];
$tag->titel =  $post['titel'];
$tag->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));
$tag->userid = $this->user['id'];  
$tag->creationdate = new Phalcon\Db\RawValue('now()');   
if($tag->save())
{ }
else
{
	foreach ($tag->getMessages() as $message)
	{
		echo $message;
	}
}

/* CONNECT A TAG TO THIS ENTITY END */
