<!DOCTYPE html>
<html>
<head>
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="browser.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<section id="wrap">
	<header>
		<hgroup>
			<h1>{$title}</h1>
			<h2>{$subtitle}</h2>
		</hgroup>
	</header>
	<nav>
{foreach from=$menu item=i}
		<li><a href="{$i.url}">{$i.name}</a></li>
{/foreach}
	</nav>	
	
{if $signedIn}
	<nav>
		<a href="?admin=addPost">Add Post</a>
		<a href="?admin=addPage">Add Page</a>
	</nav>
{/if}
	
	
{if $mode eq 'bloglist'}
	<div id="articles">
		{foreach from=$articles item=article}
		<article>
			<a href="?post={$article.id}"><h1>{$article.title}</h1></a>
			<section class="articleContent">
			{$article.desc}
			</section>
		</article>
		{/foreach}
	</div>
{elseif $mode eq 'post'}
	<div id="articles">
		<article>
			<h1>{$post.title}</h1>
			<section class="articleContent">
			{$post.desc}
			</section>
		</article>
	</div>
{elseif $mode eq 'page'}
	<div id="articles">
		<article>
			<h1>{$page.title}</h1>
			<section class="articleContent">
			{$page.desc}
			</section>
		</article>
	</div>
{elseif $mode eq 'addPost'}
<div id="articles">
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
</div>
{elseif $mode eq 'addPage'}
<div id="articles">
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
</div>
{elseif $mode eq 'added'}
<div id="articles">
	<article>
	<h1>Added</h1>
	<p>It was added to the database</p>
	</article>
</section>
{elseif $mode eq 'notAdded'}
<div id="articles">
	<article>
	<h1>Added</h1>
	<p>It was added to the database</p>
	</article>
</section>
{else}
	<div id="articles">
		<article>
			<h1>404 Not Found</h1>
			<section class="articleContent">
			Ikke funnet.
			</section>
		</article>
	</div>
{/if}
<div id="rightbar">
	<h2>dette er en test</h2>
	<div id="login">
		{if $failed}
			<span>Loggon failed!</span>
		{elseif $signedIn}
			<span>You are <a href="?login=out" alt="Sign Out">signed in</a></span>
		{else}
			<form action="?login=in" method="post">
				<label for="userId">Un:</label>
				<input type="text" name="userId" id="userId" /><br/>
				<label for="password">Pw:</label>
				<input type="password" name="password" id="password" /><br/>
				<input type="submit" value="Sign In" />
			</form>
		{/if}
	</div>	
</div>
<footer><p>Alle rettigheter &copy; Vegard Lang&aring;s, Lena Silseth og Daniel Razafimandimby.<br>Mer info om cms-et p&aring; <a href="https://github.com/sjefen6/webapp-blogg" target="_blank">github</a></p></footer>
</section>
</body>
</html>