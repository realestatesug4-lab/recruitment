<div class="bento-card card-map overflow-hidden relative min-h-[200px] rounded-lg">
    <div class="map-bg w-full h-full bg-gradient-to-br from-forest via-sage to-mint relative flex items-center justify-center overflow-hidden">
        <div class="map-dots absolute inset-0">
            <div class="map-pin absolute w-3 h-3 bg-white/50 rounded-full border-2 border-white/80 animate-ping-slow" style="top:35%;left:48%"></div>
            <div class="map-pin absolute w-3 h-3 bg-white/50 rounded-full border-2 border-white/80 animate-ping-slow" style="top:55%;left:52%;animation-delay:1s"></div>
            <div class="map-pin absolute w-3 h-3 bg-white/50 rounded-full border-2 border-white/80 animate-ping-slow" style="top:28%;left:42%;animation-delay:2s"></div>
            <div class="map-pin absolute w-3 h-3 bg-white/50 rounded-full border-2 border-white/80 animate-ping-slow" style="top:65%;left:38%;animation-delay:0.5s"></div>
        </div>
        <div class="map-overlay absolute inset-0 bg-gradient-to-br from-forest/60 to-transparent"></div>
        <div class="map-content absolute bottom-5 left-5 z-10">
            <div class="map-label text-xs uppercase tracking-wider text-white/60">Jobs across Uganda</div>
            <div class="map-city font-syne font-bold text-white">Kampala HQ</div>
            <div class="map-count inline-block bg-mint text-forest text-xs font-bold px-2.5 py-1 rounded-full mt-1.5">8,200 jobs nearby</div>
        </div>
    </div>
</div>

<style>
@keyframes ping-slow {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.3); opacity: 0.2; }
}
.animate-ping-slow {
    animation: ping-slow 3s infinite;
}
</style>
