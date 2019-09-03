<h2>{{ $titulo }}</h2>

<ul>

@foreach ($animales as $animal)
<li>{{ $animal }}</li>
@endforeach

</ul>
