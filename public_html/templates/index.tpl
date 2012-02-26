<!DOCTYPE html>
<html>
<head>
<title>{$title}</title>
</head>
<body>
	<header>
		<hgroup>
			<h1>{$title}</h1>
			<h2>{$subtitle}</h2>
		</hgroup>
	</header>
	<nav>
		{foreach $menu as $menuItem}
		<a href="{$menuItem.url}">{$menuItem.name}</a>
		{/foreach}
	</nav>
	<section id="articles">
		{foreach $articles as $article}
		<article>
			<h1>{$article.title}</h1>
			<section class="articleContent">
			{$article.content}
			</section>
		</article>
		{/foreach}
	</section>
</body>
</html>