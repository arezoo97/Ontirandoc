<?php
/*
 صفحه  نمایش لیست و مدیریت داده ها مربوط به : برچسبهای خصوصیات
	برنامه نویس: امید میلانی فرد
	تاریخ ایجاد: 94-3-2
*/
include("header.inc.php");
include("../sharedClasses/SharedClass.class.php");
include("classes/OntologyPropertyLabels.class.php");
include("classes/OntologyProperties.class.php");
HTMLBegin();
if (isset($_REQUEST["Save"])) {
	if (isset($_REQUEST["OntologyPropertyID"]))
		$Item_OntologyPropertyID = $_REQUEST["OntologyPropertyID"];
	if (isset($_REQUEST["Item_label"]))
		$Item_label = $_REQUEST["Item_label"];
	if (!isset($_REQUEST["UpdateID"])) {
		manage_OntologyPropertyLabels::Add(
			$Item_OntologyPropertyID,
			$Item_label
		);
	} else {
		manage_OntologyPropertyLabels::Update(
			$_REQUEST["UpdateID"],
			$Item_label
		);
	}
	echo SharedClass::CreateMessageBox(C_INFORMATION_SAVED);
}
$LoadDataJavascriptCode = '';
$comment = "";
if (isset($_REQUEST["UpdateID"])) {
	$obj = new be_OntologyPropertyLabels();
	$obj->LoadDataFromDatabase($_REQUEST["UpdateID"]);
	$comment = htmlentities($obj->label, ENT_QUOTES, 'UTF-8');;
}
?>
<form method="post" id="f1" name="f1">
	<?
	if (isset($_REQUEST["UpdateID"])) {
		echo "<input type=\"hidden\" name=\"UpdateID\" id=\"UpdateID\" value='" . $_REQUEST["UpdateID"] . "'>";
	}
	echo manage_OntologyProperties::ShowSummary($_REQUEST["OntologyPropertyID"]);
	echo manage_OntologyProperties::ShowTabs($_REQUEST["OntologyPropertyID"], "ManageOntologyPropertyLabels");
	?>
	<br>
	<table width="90%" border="1" cellspacing="0" align="center">
		<tr class="HeaderOfTable">
			<td align="center"><? echo C_CREATE_EDIT_LABELS ?></td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0">
					<?
					if (!isset($_REQUEST["UpdateID"])) {
					?>
						<input type="hidden" name="OntologyPropertyID" id="OntologyPropertyID" value='<? if (isset($_REQUEST["OntologyPropertyID"])) echo htmlentities($_REQUEST["OntologyPropertyID"], ENT_QUOTES, 'UTF-8'); ?>'>
					<? } ?>
					<tr>
						<td width="1%" nowrap>
							<? echo C_LABEL ?>
						</td>
						<td nowrap>
							<textarea name="Item_label" id="Item_label" cols="80" rows="5"><? echo $comment ?></textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr class="FooterOfTable">
			<td align="center">
				<input type="button" onclick="javascript: ValidateForm();" value="<? echo C_SAVE ?>">
				<input type="button" onclick="javascript: document.location='ManageOntologyPropertyLabels.php?OntologyPropertyID=<?php echo $_REQUEST["OntologyPropertyID"]; ?>'" value="<? echo C_NEW ?>">
				<input type="button" onclick="javascript: window.close();" value="<? echo C_CLOSE ?>">
			</td>
		</tr>
	</table>
	<input type="hidden" name="Save" id="Save" value="1">
</form>
<script>
	<? echo $LoadDataJavascriptCode; ?>

	function ValidateForm() {
		document.f1.submit();
	}
</script>
<?php
$res = manage_OntologyPropertyLabels::GetList($_REQUEST["OntologyPropertyID"]);
$SomeItemsRemoved = false;
for ($k = 0; $k < count($res); $k++) {
	if (isset($_REQUEST["ch_" . $res[$k]->OntologyPropertyLabelID])) {
		manage_OntologyPropertyLabels::Remove($res[$k]->OntologyPropertyLabelID);
		$SomeItemsRemoved = true;
	}
}
if ($SomeItemsRemoved)
	$res = manage_OntologyPropertyLabels::GetList($_REQUEST["OntologyPropertyID"]);
?>
<form id="ListForm" name="ListForm" method="post">
	<input type="hidden" id="Item_OntologyPropertyID" name="Item_OntologyPropertyID" value="<? echo htmlentities($_REQUEST["OntologyPropertyID"], ENT_QUOTES, 'UTF-8'); ?>">
	<br>
	<table width="90%" align="center" border="1" cellspacing="0">
		<tr bgcolor="#cccccc">
			<td colspan="4">
				<? echo C_LABELS ?>
			</td>
		</tr>
		<tr class="HeaderOfTable">
			<td width="1%"> </td>
			<td width="1%"><? echo C_ROW ?></td>
			<td width="2%"><? echo C_EDIT ?></td>
			<td><? echo C_LABEL ?></td>
		</tr>
		<?
		for ($k = 0; $k < count($res); $k++) {
			if ($k % 2 == 0)
				echo "<tr class=\"OddRow\">";
			else
				echo "<tr class=\"EvenRow\">";
			echo "<td>";
			echo "<input type=\"checkbox\" name=\"ch_" . $res[$k]->OntologyPropertyLabelID . "\">";
			echo "</td>";
			echo "<td>" . ($k + 1) . "</td>";
			echo "	<td><a href=\"ManageOntologyPropertyLabels.php?UpdateID=" . $res[$k]->OntologyPropertyLabelID . "&OntologyPropertyID=" . $_REQUEST["OntologyPropertyID"] . "\"><img src='images/edit.gif' title='<? echo C_EDIT ?>'></a></td>";
			echo "	<td>" . str_replace("\r", "<br>", htmlentities($res[$k]->label, ENT_QUOTES, 'UTF-8')) . "</td>";
			echo "</tr>";
		}
		?>
		<tr class="FooterOfTable">
			<td colspan="4" align="center">
				<input type="button" onclick="javascript: ConfirmDelete();" value="<? echo C_DELETE ?>">
			</td>
		</tr>
	</table>
</form>
<form target="_blank" method="post" action="NewOntologyPropertyLabels.php" id="NewRecordForm" name="NewRecordForm">
	<input type="hidden" id="OntologyPropertyID" name="OntologyPropertyID" value="<? echo htmlentities($_REQUEST["OntologyPropertyID"], ENT_QUOTES, 'UTF-8'); ?>">
</form>
<script>
	function ConfirmDelete() {
		if (confirm('<? echo C_T_AREUSURE ?>')) document.ListForm.submit();
	}
</script>

</html>