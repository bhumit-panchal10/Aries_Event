@php
    $address = $Order->shiiping_address1 . ', ' . $Order->shiiping_address2;
    $today = \Carbon\Carbon::now();
    $deliveryEstimate = $today->addDays(3)->format('d M, Y');
@endphp

<table
    style="width: 100%; max-width: 750px; margin: auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; font-family: 'Segoe UI', sans-serif; border: 3px solid #2a7d3e; box-shadow: 0 0 12px rgba(42, 125, 62, 0.2);">

    <!-- Header -->
    <tr>
        <td style="padding: 30px; text-align: center; color: #000;">
            <img src="https://www.getdemo.in/oro_veda/assets/images/logo.png" alt="OroVeda Logo"
                style="width: 180px; margin-bottom: 10px;">
            <h2 style="margin: 0; font-size: 24px; color: #2a7d3e;">Thank you for your order!</h2>
            <p style="margin: 5px 0 0; font-size: 14px; color: #666;">{{ config('app.name') }}</p>
        </td>
    </tr>
    <tr>
        <td>
            <hr style="border: 1px solid #8bc34a;">
        </td>
    </tr>

    <!-- Customer Info -->
    <tr>
        <td style="padding: 25px;">
            <h3 style="margin: 0 0 15px; font-size: 20px; color: #2a7d3e; border-bottom: 2px solid #d4af37;">Customer
                Details</h3>
            <table style="width: 100%; font-size: 14px; color: #444;">
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>{{ $Order->shipping_cutomerName }}</td>
                </tr>
                <tr>
                    <td><strong>Mobile:</strong></td>
                    <td>{{ $Order->shipping_mobile ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $Order->shipping_email ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Address:</strong></td>
                    <td>{{ $address }}</td>
                </tr>
                <tr>
                    <td><strong>City:</strong></td>
                    <td>{{ $Order->shipping_city }}</td>
                </tr>
                <tr>
                    <td><strong>State:</strong></td>
                    <td>{{ $Order->shiiping_state ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Country:</strong></td>
                    <td>{{ $CountryName ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Pincode:</strong></td>
                    <td>{{ $Order->shipping_pincode }}</td>
                </tr>
                <tr>
                    <td><strong>Expected Delivery:</strong></td>
                    <td>{{ $deliveryEstimate }}</td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Order Summary -->
    <tr>
        <td
            style="background: linear-gradient(135deg, #2a7d3e, #8bc34a); color: white; padding: 10px 20px; font-size: 18px; font-weight: bold;">
            Order Summary
        </td>
    </tr>

    <tr>
        <td style="padding: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background-color: #2a7d3e; font-weight: bold; color: white;">
                        <th style="padding: 10px;">Sr</th>
                        <th style="padding: 10px;">Product</th>
                        <th style="padding: 10px;">Image</th>
                        <th style="padding: 10px;">Size</th>
                        <th style="padding: 10px;">Qty</th>
                        <th style="padding: 10px;">Rate</th>
                        <th style="padding: 10px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($OrderDetail as $cartItem)
                        <tr style="background-color: {{ $i % 2 == 0 ? '#f6fff6' : '#e8f5e9' }};">
                            <td style="padding: 10px; text-align: center;">{{ $i++ }}</td>
                            <td style="padding: 10px;">{{ $cartItem->productname }}</td>
                            <td style="padding: 10px; text-align: center;">
                                <img src="https://www.getdemo.in/oro_veda/uploads/product/thumbnail/{{ $cartItem->photo }}"
                                    width="40" height="40" style="border-radius: 5px;">
                            </td>
                            <td style="padding: 10px; text-align: center;">
                                {{ $cartItem->product_attribute_qty . ' (' . $cartItem->name . ')' }}
                            </td>
                            <td style="padding: 10px; text-align: center;">{{ $cartItem->quantity }}</td>
                            <td style="padding: 10px; text-align: center;">{{ $cartItem->currency }}
                                {{ $cartItem->rate }}</td>
                            <td style="padding: 10px; text-align: center;">{{ $cartItem->currency }}
                                {{ $cartItem->quantity * $cartItem->rate }}</td>
                        </tr>
                    @endforeach

                    <tr style="background-color: #c8e6c9; font-weight: bold; color: #000;">
                        <td colspan="5"></td>
                        <td style="padding: 10px 15px; text-align: right;">Total</td>
                        <td style="padding: 10px 15px; text-align: right;">{{ $Order->currency }} {{ $Order->amount }}
                        </td>
                    </tr>
                    <tr style="background-color: #aed581; font-weight: bold; color: #000;">
                        <td colspan="5"></td>
                        <td style="padding: 10px 15px; text-align: right;">Discount</td>
                        <td style="padding: 10px 15px; text-align: right;">{{ $Order->currency }}
                            {{ $Order->discount }}</td>
                    </tr>
                    <tr style="background: linear-gradient(135deg, #2a7d3e, #8bc34a); font-weight: bold; color: white;">
                        <td colspan="5"></td>
                        <td style="padding: 10px 15px; text-align: right;">Net Amount</td>
                        <td style="padding: 10px 15px; text-align: right;">{{ $Order->currency }}
                            {{ $Order->netAmount }}</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td style="padding: 20px; background-color: #f6f6f6; text-align: center; font-size: 13px; color: #555;">
            <p style="margin: 5px 0;"><strong>Note:</strong> Your order will be dispatched in 3 working days.</p>
            <p style="margin: 5px 0;">Need help? Call <strong style="color: #2a7d3e;">+91 81560 88203</strong></p>
            <p style="margin: 5px 0; color: #8bc34a;">&copy; {{ now()->year }} {{ config('app.name') }} | Crafted
                with 💚 by OroVeda</p>
        </td>
    </tr>
</table>
