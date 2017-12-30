<?php
/**
 * Created by PhpStorm.
 * User: idea
 * Date: 2017-12-31
 * Time: 오전 2:43
 */
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>코드이그나이터를 이용한 CSRF 방어 예제</title>
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script>
        $(document).ready(function (e) {

            $('#form').submit(function () {

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function (data) {

                        /* ajax 의 결과값을 처리 */
                        $('#ajax_result').html(data);

                        /* csrf 갱신값을 처리 */
                        $('#csrf').val($('#token').data('value'));

                    },
                    error: function (xhr, status, error) {

                        /* 에러 출력 */
                        alert('error:' + xhr + '/status:' + status+' /error:'+error);
                    }
                });

                return false;

            })

        });
    </script>

</head>
<body>
<div id="ajax_result">
</div>
<form id="form" method="post" action="<?= site_url('ajax/test'); ?>">
    <input type="hidden" id="<?= $this->security->get_csrf_token_name(); ?>" name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>"/>

    <input type="text" name="text" placeholder="테스트 값을 입력"/>
    <input type="submit" value="전송"/>
</body>
</html>
