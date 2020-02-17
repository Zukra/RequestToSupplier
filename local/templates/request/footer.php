<? use Bitrix\Main\Page\Asset;

if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php /*</div>
</div>*/ ?>

</div><!-- content -->
<footer class="footer">
    <div class="footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <? $APPLICATION->IncludeFile(
                        $APPLICATION->GetTemplatePath("include_areas/copyright.php"),
                        [],
                        ["MODE" => "html"]
                    ); ?>
                </div>
            </div>
        </div>
    </div>
</footer>

</div><!-- wrapper -->

<?php/*
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="/local/assets/js/bootstrap.offcanvas.min.js" type="text/javascript"></script>
<script src="/local/assets/js/slick.js" type="text/javascript"></script>
<script src="/local/assets/js/script.js" type="text/javascript"></script>
<script src="/local/assets/js/custom.js" type="text/javascript"></script>
*/?>

<script>"use strict";
    $(function () {
        $('.example_help').popover({
          trigger: 'focus'
        });
        $('.error_area').tooltip();
    });
</script>

</body>
</html>