<h1>条件语句</h1>
@if($level == 1)
    简单<br/>
@elseif($level == 2)
    一般<br/>
@else
    困难<br/>
@endif
<h1>循环例子</h1>
@foreach($products as $product)
    {{$product->title}}<br/>
@endforeach