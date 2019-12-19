    <!--Footer Section Start-->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <footer>
                <div class="copyright-text">Copyright &copy; {{ date('Y')}} Hapity. All rights reserved.</div>
            </footer>
        </div>
    <!--Footer Section End-->
    </div>
</div>
</div>

<!--Main End-->
<!-- Modal start -->
<!--Model End-->

</div>
<!--Wrapper End-->
</body>
<?php
if(isset($_GET['delete'])) {?>
<script>
    alert('Broadcast is deleted successfully.')
</script>
<?php }?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<?php

$datetimes = false;

if(isset($_GET['datetimes']) && $_GET['datetimes'] != "") {
$datetimes = $_GET['datetimes'];
$datetimes = explode('-', $datetimes);
}

?>
<script>
$(document).ready(function(){
    $('#close-btn').click(function(){
        close_broadcast();
    });
    $('#model-cross').click(function(){
        close_broadcast();
    });
    $('#close-modal').click(function(){
        close_broadcast();
    });
});

<?php if($datetimes): ?>
    $(function() {
        $('input[name="datetimes"]').daterangepicker({
            startDate: '<?php echo $datetimes[0] ?>',
            endDate: '<?php echo $datetimes[1] ?>',
            locale: {
                format: 'YYYY/MM/DD'
            }
        });
    });
<?php else: ?>
    $(function() {
        $('input[name="datetimes"]').daterangepicker({
            locale: {
                format: 'YYYY/MM/DD'
            }
        });
    });
<?php endif; ?>


</script>
@stack('admin-script')
</html>