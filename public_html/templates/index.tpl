<!DOCTYPE html>
<html>
<head>
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="browser.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<header>
		<hgroup>
			<h1>{$title}</h1>
			<h2>{$subtitle}</h2>
		</hgroup>
	</header>
	<nav>
{foreach from=$menu item=i}
		<a href="{$i.url}">{$i.name}</a>
{/foreach}
{if $failed}
	<span>Loggon failed!</span>
{elseif $signedIn}
	<span>You are <a href="?login=out" alt="Sign Out">signed in</a></span>
{else}
	<form action="?login=in" method="post">
		<label for="userId">Un:</label>
		<input type="text" name="userId" id="userId" />
		<label for="password">Pw:</label>
		<input type="password" name="password" id="password" />
		<input type="submit" value="Sign In" />
	</form>
{/if}
	</nav>
{if $signedIn}
	<nav>
		<a href="?admin=addPost">Add Post</a>
		<a href="?admin=addPage">Add Page</a>
	</nav>
{/if}
	
	
{if $mode eq 'bloglist'}
	<section id="articles">
		{foreach from=$articles item=article}
		<article>
			<a href="?post={$article.id}"><h1>{$article.title}</h1></a>
			<section class="articleContent">
			{$article.desc}
			</section>
		</article>
		{/foreach}
	</section>
{elseif $mode eq 'post'}
	<section id="articles">
		<article>
			<h1>{$post.title}</h1>
			<section class="articleContent">
			{$post.desc}
			</section>
		</article>
	</section>
{elseif $mode eq 'page'}
	<section id="articles">
		<article>
			<h1>{$page.title}</h1>
			<section class="articleContent">
			{$page.desc}
			</section>
		</article>
	</section>
{elseif $mode eq 'addPost'}
<section id="articles">
	<article>
	<h1>Add Post</h1>
	<form action="?admin=addPost" method="post">
		<label for="title">Title:</label>
		<input type="text" name="title" required="required" /><br>
		<label for="id">Id:</label>
		<input type="text" name="id" required="required" /><br>
		<label for="desc">Content:</label><br>
		<textarea rows="30" cols="100" name="desc" required="required" ></textarea><br>
		<input type="submit" value="Add Post" />
	</form>
	</article>
</section>
{elseif $mode eq 'addPage'}
<section id="articles">
	<article>
	<h1>Add Page</h1>
	<form action="?admin=addPage" method="post">
		<label for="title">Title:</label>
		<input type="text" name="title" required="required" /><br>
		<label for="id">Id:</label>
		<input type="text" name="id" required="required" /><br>
		<label for="desc">Content:</label><br>
		<textarea rows="30" cols="100" name="desc" required="required" ></textarea><br>
		<input type="submit" value="Add Page" />
	</form>
	</article>
</section>
{elseif $mode eq 'added'}
<section id="articles">
	<article>
	<h1>Added</h1>
	<p>It was added to the database</p>
	</article>
</section>
{elseif $mode eq 'notAdded'}
<section id="articles">
	<article>
	<h1>Added</h1>
	<p>It was added to the database</p>
	</article>
</section>
{else}
	<section id="articles">
		<article>
			<h1>404 Not Found</h1>
			<section class="articleContent">
			Ikke funnet.
			</section>
		</article>
	</section>
{/if}
</body>
</html>