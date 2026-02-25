</main>
<footer class="text-center mt-5 py-3 border-top">
    <p>&copy; <?= date('Y') ?> - Aplicaciones WEB - Profesor lic. Juan Pablo Cesarini</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="module" src=" <?= Env::get('BASE_URL') ?>/js/modules/authMain.js"></script>
</body>

</html>