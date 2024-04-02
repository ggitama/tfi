<div class="body flex-grow-1 p-4 ms-5 me-5" style="background-color: #ffff;">

    <!-- <div class="row mt-4">
        <div class="col-sm-12">
            <h4>A. Sales</h4>
            <div class="table-responsive">
                <table id="ListSales" class="table table-bordered table-striped text-center align-middle dataTable no-footer dtr-inline" width="100%">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>BU</th>
                            <th>Month</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tr>
                        <td rowspan="5">Net Sales</td>
                        <td>F&B</td>
                        <td>Jan, Feb</td>
                        <td>Bulan Jan & Feb pada PnL dimasukan semua Departmen 5 (tidak hanya Mami & Resto)</td>
                    </tr>
                    <tr>
                        <td>Trans Living and Hardware</td>
                        <td>Feb</td>
                        <td>Bulan Feb pada PnL dimasukan Metro</td>
                    </tr>
                    <tr>
                        <td>Branded Counter</td>
                        <td>Feb, Mar, Apr, Mei, Jun, Sep, Oct, Nov, Dec</td>
                        <td>Aman</td>
                    </tr>
                    <tr>
                        <td>Okidoki</td>
                        <td>Feb, Mar, Apr, Mei, Jun, Jul, Aug, Sep, Oct, Nov, Dec</td>
                        <td>Selisih seluruh bulan dikarenakan PnL mengikutsertakan Metro</td>
                    </tr>
                    <tr>
                        <td>Optic</td>
                        <td>Jan</td>
                        <td>Aman</td>
                    </tr>
                </table>
            </div>
        </div>
    </div> -->

    <div class="row mt-4">
        <div class="col-sm-12">
            <h4>A. Store</h4>
            <p>Data Berdasarkan performance dari store Retail dengan detail terlampir :</p>
            <blockquote>
                <ol type="1">
                    <li>Main Store (Main dan ARI)</li>
                    <li>Express</li>
                    <li>TDR Trans Hello</li>
                    <li>Smart Sales</li>
                </ol>
            </blockquote>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-sm-12">
            <h4>B. Business Unit</h4>
            <p>Data Berdasarkan performance dari business unit dengan detail terlampir :</p>
            <blockquote>
                <ol type="1">
                    <li>FMCG</li>
                    <li>Fresh</li>
                    <li>Dept Store (menggunakan data sales dari system TDS dan Profit)</li>
                    <li>Electronics</li>
                    <li>Hardware</li>
                    <li>Living</li>
                    <li>Okidoki</li>
                    <li>F&B (hanya menggunakan departemen dengan kode 52 / Mami dan Resto)</li>
                    <li>Branded Counter</li>
                    <!-- <li>Sport & Lifestyle(data sales sampai bulan juli 2022)</li>
                    <li>Optics (data sales bulan jan 2022)</li> -->
                </ol>
            </blockquote>
            <p>Beberapa business unit digabung dengan business unit lain dengan detail sebagai berikut:</p>
            <blockquote>
                <ol type="1">
                    <li>Branded Counter digabung dengan Trans Living untuk data sales, headcount dan luas area</li>
                    <li>Living, Hardware dan Okidoki digabung untuk data headcount</li>
                </ol>
            </blockquote>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-sm-12">
            <h4>C. Profit and Loss</h4>
            <!-- <p>Data yang sudah final yaitu data sampai dengan Dec 2022, data Jan dan Feb 2023 menggunakan asumsi average satu tahun ke belakang.</p> -->
            <p>Data yang sudah final yaitu data sampai dengan Jan 2023. Data PnL Feb 2023 sampai saat ini masih dalam tahap validasi dan belum bisa ditampilkan di dashboard.</p>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12">
            <h4>D. Breakage and Adj Inventory</h4>
            <!-- <p>Data yang sudah final yaitu data sampai dengan Dec 2022, data Jan dan Feb 2023 menggunakan asumsi average satu tahun ke belakang.</p> -->
            <blockquote>
                <ul>
                    <li>Breakage: Total nominal inventory yang tidak layak jual (Adjustment Reason = “B + G”).</li>
                    <li>Adjustment Inventory: Total nominal Hasil Inventory (Adjustment Reason = “I + C”).</li>
                </ul>
            </blockquote>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-sm-12">
            <h4>E. Stock Aging Matrix (Inventory Ageing)</h4>
            <table id="StockAging" class="table table-bordered table-striped text-center align-middle dataTable no-footer dtr-inline" width="100%">
                <thead>
                    <tr>
                        <th>BUSINESS UNIT</th>
                        <th style="background-color:#00ff00;" >GREEN</th>
                        <th style="background-color:#ffff00;" >YELLOW</th>
                        <th style="background-color:#FF0000;" >RED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>FMCG</td>
                        <td> < 6 bulan</td>
                        <td> 6 s/d 9 bulan</td>
                        <td> > 9 bulan</td>
                    </tr>
                    <tr>
                        <td>Dept Store</td>
                        <td> < 7 bulan</td>
                        <td> 7 s/d 12 bulan</td>
                        <td> > 12 bulan</td>
                    </tr>
                    <tr>
                        <td>Elpro</td>
                        <td> < 6 bulan</td>
                        <td> 6 s/d 9 bulan</td>
                        <td> > 9 bulan</td>
                    </tr>
                    <tr>
                        <td>Trans Living/Hardware/Okidoki/Branded Counter</td>
                        <td> < 6 bulan</td>
                        <td> 6 s/d 18 bulan</td>
                        <td> > 18 bulan</td>
                    </tr>
                </tbody>
            </table>
            <p class="text-center">*Sesuai kesepakatan dengan Territory Director tanggal 6 Maret 2023</p>
        </div>
    </div>



    <div class="row mt-4">
        <div class="col-sm-12">
            <h1 class="text-center">List Store yang digunakan</h1>
            <table id="ListStore" class="table table-bordered table-striped text-center align-middle dataTable no-footer dtr-inline" width="100%">
                <thead>
                    <tr>
                        <th>Store Location</th>
                        <!-- <th>Status Tutup</th> -->
                        <th>Region</th>
                        <th>Territory</th>
                        <th>Main</th>
                        <th>Express</th>
                        <th>TDR Trans Hello</th>
                        <th>Smart Sales</th>
                    </tr>
                </thead>
                <?php foreach ($listStore as $store) : ?>
                    <tr>
                        <td><?= $store['Store_Location'] ?></td>
                        <!-- <td><?= $store['Status_Tutup'] ?></td> -->
                        <td><?= $store['Region'] ?></td>
                        <td><?= $store['Territory'] ?></td>
                        <td><?= $store['Main'] ?></td>
                        <td><?= $store['Express'] ?></td>
                        <td><?= $store['TDR_Trans_Hello'] ?></td>
                        <td><?= $store['Smart_Sales'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $("#ListStore").DataTable({
            "responsive": true,
        });

        // $("#ListSales").DataTable({
        //     "responsive": true,
        //     'rowsGroup': ['Net Sales']
        // });
    });
</script>