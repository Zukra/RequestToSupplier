<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

</div>
<br/>
<? $APPLICATION->IncludeFile(
    $APPLICATION->GetTemplatePath("include_areas/copyright.php"),
    [],
    ["MODE" => "html"]
); ?>

</div>
</body>
</html>