        </main>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <?php if(isset($this->var['answer'])) : ?>
        <script src="<?=$_SERVER['BASE_URI']?>/assets/js/answer.js"></script>
    <?php endif; ?>
    <?php if(isset($this->var['js'])) : ?>
        <script src="<?=$_SERVER['BASE_URI']?>/assets/js/<?=$this->var['js']?>.js"></script>
    <?php endif; ?>
    </body>
</html>