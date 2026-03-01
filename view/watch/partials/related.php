<div class="related-movies" style="
margin-top:40px;
padding:25px;
background:linear-gradient(180deg,#0f0f15,#090909);
border-radius:14px;
">

<div class="related-header" style="margin-bottom:20px;">
<h3 style="font-size:20px;color:#fff;margin-bottom:5px;">
🎯 Phim liên quan
</h3>

<div style="color:#aaa;font-size:14px;">
Có thể bạn sẽ thích
</div>
</div>


<div style="
display:grid;
grid-template-columns:repeat(auto-fill,minmax(160px,1fr));
gap:20px;
">

<?php if($related && $related->num_rows > 0): ?>

<?php while($r = $related->fetch_assoc()): ?>

<a href="/movie-site/view/watch/watch.php?slug=<?= urlencode($r['slug']) ?>"
style="
text-decoration:none;
color:white;
transition:transform .2s;
">

<div style="
width:100%;
aspect-ratio:2/3;
overflow:hidden;
border-radius:10px;
box-shadow:0 6px 18px rgba(0,0,0,.5);
">

<img src="/movie-site/<?= htmlspecialchars($r['poster']) ?>"
style="
width:100%;
height:100%;
object-fit:cover;
display:block;
">

</div>

<div style="
margin-top:8px;
font-size:14px;
text-align:center;
white-space:nowrap;
overflow:hidden;
text-overflow:ellipsis;
">

<?= htmlspecialchars($r['title']) ?>

</div>

</a>

<?php endwhile; ?>

<?php endif; ?>

</div>

</div>
