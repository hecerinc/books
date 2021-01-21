<?php

$_GETLISTS = 'SELECT DISTINCT id, parent_id, name FROM `collections` `collection` 
JOIN `collection_closure` `closure`
ON collection.id = closure.descendant';

require_once 'conex.php';

$db = DataConnection::getDBConnection();

$result = $db->conn->query($_GETLISTS);
if($result):
	$cats = $result->fetch_all(MYSQLI_ASSOC);
endif;

function get_parent_chain($cat, $parent_chain) {
	$res = new \Ds\Stack();
	$x = $cat['parent_id'];
	while($x !== NULL) {
		$res->push($x);
		$x = $parent_chain[$x];
	}
	return $res;
}
function clean_cats($cats) {
	$res = [];
	$parent_chain = [];
	$i = 0;
	foreach($cats as $cat) {
		$parent_chain[$cat['id']] = $cat['parent_id'];
		if(!isset($cat['children'])) {
			$cat['children'] = [];
		}
		if($cat['parent_id'] == NULL) {
			$res[$cat['id']] = $cat;
		}
		else {
			$cat_parent_chain = get_parent_chain($cat, $parent_chain);

			$test = &$res;
			while(!$cat_parent_chain->isEmpty()) {
				$p_id = $cat_parent_chain->pop();
				$x = &$test[$p_id];
				$test = &$x['children'];
			}
			$test[$cat['id']] = $cat;
		}
		$i++;
	}
	return $res;
}

function print_cat_options($cats, $depth = 0) {

	foreach($cats as $cat) {
		$name = $depth == 0 ? $cat['name'] : str_repeat("&nbsp;&nbsp;&nbsp;", $depth)."- ".$cat['name'];
		$id = $cat['id'];
		echo "<option value=\"$id\">$name</option>";
		print_cat_options($cat['children'], $depth+1);
	}
}
/*
 *
23 => 
	id => 23
	name => Math
	parent_id => NULL
	children => [
		22 => 
			id => 22
			name => Something
			children =>
				20 => 
		21 => 
			id => 21
			name => CS
			children => []
	]
*/ 

?>

<?php $hcats = clean_cats($cats); ?>

<div class="row">
	<div class="col-6">
		<form action="process.php" class="new-book-form mt-5 form-group" method="post" enctype="multipart/form-data">
			<?php if(isset($is_edit)): ?>
				<input type="hidden" name="edit_id" value="<?= $book_id ?>">
			<?php endif; ?>
			<input
				autofocus
				placeholder="Title"
				name="title"
				id="title"
				type="text"
				value="<?= isset($is_edit) ?  $book['name']: '' ?>"
			/>
			<input
				placeholder="Author(s)"
				name="authors"
				id="authors"
				type="text"
				value="<?= isset($is_edit) ?  $book['authors'] : '' ?>"
			/>
			<input placeholder="ISBN" name="isbn" id="isbn" type="text" value="<?= isset($is_edit) ? $book['isbn'] : '' ?>" />
			<input
				placeholder="Where is it backuped up?"
				name="backup"
				id="backup"
				type="text"
				value="<?= isset($is_edit) ?  $book['where_backup'] : '' ?>"
			/>
			<input
				placeholder="Local path"
				name="path"
				id="path"
				type="text"
				value="<?= isset($is_edit) ?  $book['path'] .  '/' .  $book['filename'] : '' ?>"
			/>
			<input
				placeholder="Amazon link"
				name="amazon"
				id="amazon"
				type="text"
				value="<?= isset($is_edit) ?  $book['amazon_link'] : '' ?>"
			/>
			<select name="lists[]" id="lists" multiple>
				<?php print_cat_options($hcats) ?>
			</select>
			<input placeholder="Cover" name="cover" id="cover" type="file" />
			<input type="submit" value="Submit" class="submit-btn">
		</form>
	</div>
	<?php if(isset($is_edit)): ?>
		<div class="col-6 pl-5 mt-5">
			<img class="coverimg--bookform" src="covers/<?= $book['coverimg'] ?>" alt="<?= $book['name'] ?> cover">
		</div>
	<?php endif; ?>
</div>

<?php $vblock->start('scripts'); ?>
	<link rel="stylesheet" href="css/selectize.min.css">
	<link rel="stylesheet" href="css/selectize-default.min.css">
	<script src="js/selectize.min.js"></script>
	<script>
		$("#lists").selectize({placeholder: 'Associate collections', render: {
			item: (data, escape) => {
				let name = escape(data['text']);
				name = name.trim();
				if(name[0] === '-') {
					name = name.substring(1);
				}
				return '<div class="item">' + name + '</div>';
			}
		}});
		"item_add"
	</script>
<?php $vblock->end(); ?>
