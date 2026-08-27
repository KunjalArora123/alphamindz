        </div>
        <!-- Page Content End -->
    </div>
    <!-- Main Wrapper End -->

    <!-- Global Scripts -->
    <?php if(isset($use_editor) && $use_editor): ?>
    <!-- CKEditor Rich Text Editor -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
      if (document.getElementById('editor_content')) {
          CKEDITOR.replace('editor_content', {
              height: 300,
              removeButtons: 'PasteFromWord',
              versionCheck: false
          });
      }
    </script>
    <?php endif; ?>
</body>
</html>
