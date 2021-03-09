<div class="step_7_bar">
    @for($i=1;$i<=7;$i++)
        <span class="step_bar step{{$i}} {{($index>=$i)?'active':''}}"></span>
    @endfor
</div>
<div id="step_index" class="step_count" data-index="{{$index}}">
    <i class="nm">{{$index}}</i>/7 Step
</div>