{* Бегущая строка. *}
<div class="ticker__row {$mod|default:''}">
    <div class="ticker__track {$track|default:''}">
        {for $i = 1 to 6}
            <span class="ticker__word">{$a}</span>
            <span class="ticker__word">✶</span>
            <span class="ticker__word">{$b}</span>
            <span class="ticker__word">✶</span>
        {/for}
    </div>
</div>
