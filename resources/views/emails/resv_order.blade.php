<p class="mb-4">{{ $user['name']}}様</p>

<p class="mb-4">店舗から発注がありました。</p>

追加発注リスト

<ul class="mb-4">
    {{-- <li>更新日：{{ \Carbon\Carbon::parse($report_info['updated_at'])->format("y/m/d H:i") }}</li> --}}
    <li>Order_Id：{{ $order_info['order_id'] }}</li>
    <li>店舗：{{ $order_info['shop_name'] }}</li>
    <li>発注者名：{{ $order_info['name'] }}</li>
</ul>

<br>

アプリで確認をしてください。<br><br>

https://shop.dijon1988.net

<br><br>

*****     Dijon Co.Ltd.     *****

