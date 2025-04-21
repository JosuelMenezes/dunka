<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome *</label>
            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror"
                value="{{ old('nome', $produto->nome ?? '') }}" required>
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" name="codigo" id="codigo" class="form-control @error('codigo') is-invalid @enderror"
                value="{{ old('codigo', $produto->codigo ?? '') }}">
            @error('codigo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ean" class="form-label">EAN/GTIN</label>
            <input type="text" name="ean" id="ean" class="form-control @error('ean') is-invalid @enderror"
                value="{{ old('ean', $produto->ean ?? '') }}">
            @error('ean')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ncm" class="form-label">NCM</label>
            <input type="text" name="ncm" id="ncm" class="form-control @error('ncm') is-invalid @enderror"
                value="{{ old('ncm', $produto->ncm ?? '') }}">
            @error('ncm')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label for="preco" class="form-label">Preço *</label>
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" step="0.01" min="0" name="preco" id="preco"
                    class="form-control @error('preco') is-invalid @enderror"
                    value="{{ old('preco', $produto->preco ?? '') }}" required>
                @error('preco')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="variacao_ipi" class="form-label">Variação IPI (%)</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" max="100" name="variacao_ipi" id="variacao_ipi"
                    class="form-control @error('variacao_ipi') is-invalid @enderror"
                    value="{{ old('variacao_ipi', $produto->variacao_ipi ?? 0) }}">
                <span class="input-group-text">%</span>
                @error('variacao_ipi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="estoque" class="form-label">Estoque *</label>
            <input type="number" name="estoque" id="estoque" class="form-control @error('estoque') is-invalid @enderror"
                value="{{ old('estoque', $produto->estoque ?? 0) }}" required>
            @error('estoque')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="industria_id" class="form-label">Indústria *</label>
            <select name="industria_id" id="industria_id" class="form-control @error('industria_id') is-invalid @enderror" required>
                <option value="">Selecione</option>
                @foreach($industrias as $industria)
                    <option value="{{ $industria->id }}" {{ (old('industria_id', $produto->industria_id ?? '') == $industria->id) ? 'selected' : '' }}>
                        {{ $industria->nome }}
                    </option>
                @endforeach
            </select>
            @error('industria_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" rows="4"
                class="form-control @error('descricao') is-invalid @enderror">{{ old('descricao', $produto->descricao ?? '') }}</textarea>
            @error('descricao')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label for="foto" class="form-label">Foto do Produto</label>
            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror">
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if(isset($produto) && $produto->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/produtos/' . $produto->foto) }}"
                        alt="{{ $produto->nome }}" class="img-thumbnail" style="max-height: 150px">
                    <div class="form-text">Imagem atual. Envie uma nova para substituir.</div>
                </div>
            @endif
        </div>
    </div>
</div>
