<p class="mb-4">{{ $user['name']}}様</p>

<p class="mb-4">発注が登録されました。</p>

発注Data

<ul class="mb-4">
    {{-- <li>更新日：{{ \Carbon\Carbon::parse($report_info['updated_at'])->format("y/m/d H:i") }}</li> --}}
    <li>Order_Id：{{ $order_info['order_id'] }}</li>
    <li>発注日：{{ \Carbon\Carbon::parse($order_info['created_at'])->format("y/m/d H:i") }}</li>
    <li>発注者名：{{ $order_info['name'] }}</li>
</ul>

<br>

アプリで確認をしてください。<br><br>

https://buyer-supoort.dijon1988.net

<br><br>

*****     Dijon Co.Ltd.     *****

