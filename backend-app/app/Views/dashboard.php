<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<script>
    window.location.href = "<?= base_url('dashboard') ?>";
</script>
<?= $this->endSection() ?>
