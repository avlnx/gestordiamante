@extends('layouts.default')


@section('content')

<h2>Produtos <small>Produtos cadastrados</small></h2>
<p>
@if(Auth::user()->is_admin)
{{ HTML::linkRoute('products.new', "+ Novo Produto" ,[], array('title' => 'Criar novo produto', 'class' => 'btn btn-primary')) }}&nbsp;
@endif

</p>


<p class='lead'>
    Clique sobre uma categoria para visualizar os produtos
    {{ HTML::linkRoute('categories.new', "+ Nova Categoria" ,[], array('title' => 'Criar nova categoria', 'class' => 'btn btn-small btn-primary')) }}
</p>
@foreach($categories as $category)

<p class='lead'>
    <a class='no-format' id='table-toggler-{{ $category->id }}' href='#'>{{ $category->name }}</a>
    @if(Auth::user()->is_admin)
        @if($category->is_protected && !Auth::user()->tenant->is_model)
            <span class='label label-info'>Protegida</span>
        @else
            {{ HTML::linkRoute('categories.edit', 'Editar', array($category->id), array('class' => 'btn btn-mini'))}}
            {{ HTML::linkRoute('categories.delete', 'Deletar!', array($category->id), array('class' => 'btn btn-mini btn-danger confirm'))}}
        @endif
    @else
        &nbsp;
    @endif
</p>

<div id='table-wrapper-{{ $category->id }}' style='display:none'>
    <table class='table table-hover table-condensed'>
        <thead>
        <tr>
            <th>Código</th><th>Nome</th><th>Descrição</th><th>Preço</th><th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php $found = False ?>
        @foreach($products as $product)

        @if($product->category_id == $category->id)
        <?php $found = True ?>
        <tr>
            <td><small>{{ $product->ref }}</small></td>
            <td>
                {{ HTML::linkRoute('products.focus', $product->name , array($product->id), array('title' => $product->description)) }}
            </td>
            <td>
                {{ $product->description }}
            </td>
            <td>
                R$ {{ $product->price }}
            </td>
            <td>
                @if(Auth::user()->is_admin)
                    @if($product->is_protected && !Auth::user()->tenant->is_model)
                        <span class='label label-info'>Protegido</span>
                    @else
                        {{ HTML::linkRoute('products.edit', 'Editar', array($product->id), array('class' => 'btn btn-mini'))}}
                        {{ HTML::linkRoute('products.delete', 'Deletar!', array($product->id), array('class' => 'confirm btn btn-mini btn-danger'))}}
                    @endif
                @else
                &nbsp;
                @endif
            </td>
        </tr>
        @endif
        @endforeach
        @if(!$found)
        <tr><td colspan='3'><p class='text-info'>Nenhum produto nesta categoria</p></td></tr>
        @endif
        </tbody>
    </table>
</div>

@endforeach
@if(count($categories)==0)
<p>Nenhuma categoria cadastrada</p>
@endif

@stop

@section('scripts')
@foreach($categories as $category)
$('#table-toggler-{{ $category->id }}').on('click', function(event){
event.preventDefault();
$('#table-wrapper-{{ $category->id }}').slideToggle();
});
@endforeach
@stop
