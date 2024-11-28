<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Gráfico de Indicadores</h2>
<form id="form-grafico" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <label for="indicador" class="form-label">Tipo de Indicador</label>
            <select id="indicador" class="form-select">
                <option value="uf">UF</option>
                <option value="dolar">Dólar</option>
                <option value="euro">Euro</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="fecha-desde" class="form-label">Desde</label>
            <input type="date" id="fecha-desde" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="fecha-hasta" class="form-label">Hasta</label>
            <input type="date" id="fecha-hasta" class="form-control" required>
        </div>
    </div>
    <button type="submit" class="btn btn-secondary mt-3"><i class="bi bi-graph-up-arrow"></i> Generar Gráfico </button>
</form>
<div class="mt-4">
    <canvas id="grafico-indicadores" height="100"></canvas>
</div>
<!-- Script Indicadores -->
<script src="/js/graficoIndicadores.js"></script>
<?= $this->endSection() ?>
