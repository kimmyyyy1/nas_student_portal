@props(['url'])
<tr>
<td class="header" style="text-align: center; padding: 30px 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 0 auto;">
        <tr>
            <td align="right" style="padding-right: 15px; width: 50%; vertical-align: middle;">
                <a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
                    <img src="{{ asset('images/nas/NAS LOGO.png') }}" 
                         alt="NAS Logo" 
                         style="width: 140px; height: auto; display: block; margin-left: auto; border: none; outline: none; text-decoration: none;">
                </a>
            </td>
            <td align="left" style="padding-left: 15px; width: 50%; vertical-align: middle;">
                <a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
                    <img src="{{ asset('images/nas/NASCENT SAS.png') }}" 
                         alt="NASCENT SAS Logo" 
                         style="width: 160px; height: auto; display: block; margin-right: auto; border: none; outline: none; text-decoration: none;">
                </a>
            </td>
        </tr>
    </table>
</td>
</tr>
