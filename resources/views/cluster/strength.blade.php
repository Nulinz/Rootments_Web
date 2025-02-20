@extends ('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_strength.css') }}">

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Cluster Store Strength</h4>
        </div>

        <!-- Cluster Tabs -->
        <?php include('./cluster_tabs.php'); ?>

        <div class="container px-0 mt-3 listtable">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Stores</th>
                            <th class="thdark">Store Manager</th>
                            <th class="thdark">Asst. Store Manager</th>
                            <th class="thdark">Sales Executive</th>
                            <th class="thdark">Quality Check</th>
                            <th class="thdark">Tailor</th>
                            <th class="thdark">Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tddark">Store 1</td>
                            <td>1 / <span class="green">1</span></td>
                            <td>1 / <span class="green">1</span></td>
                            <td>5 / <span class="green">5</span></td>
                            <td>3 / <span class="green">3</span></td>
                            <td>3 / <span class="red">5</span></td>
                            <td>9 / <span class="red">10</span></td>
                        </tr>
                        <tr>
                            <td class="tddark">Store 2</td>
                            <td>1 / <span class="green">1</span></td>
                            <td>1 / <span class="green">1</span></td>
                            <td>5 / <span class="green">5</span></td>
                            <td>3 / <span class="green">3</span></td>
                            <td>3 / <span class="red">5</span></td>
                            <td>9 / <span class="red">10</span></td>
                        </tr>
                        <tr>
                            <td class="tddark">Store 3</td>
                            <td>1 / <span class="green">1</span></td>
                            <td>1 / <span class="green">1</span></td>
                            <td>5 / <span class="green">5</span></td>
                            <td>3 / <span class="green">3</span></td>
                            <td>3 / <span class="red">5</span></td>
                            <td>9 / <span class="red">10</span></td>
                        </tr>
                        <tr>
                            <td class="tddark">Store 4</td>
                            <td>1 / <span class="green">1</span></td>
                            <td>1 / <span class="green">1</span></td>
                            <td>5 / <span class="green">5</span></td>
                            <td>3 / <span class="green">3</span></td>
                            <td>3 / <span class="red">5</span></td>
                            <td>9 / <span class="red">10</span></td>
                        </tr>
                        <tr>
                            <td class="tddark">Store 5</td>
                            <td>1 / <span class="green">1</span></td>
                            <td>1 / <span class="green">1</span></td>
                            <td>5 / <span class="green">5</span></td>
                            <td>3 / <span class="green">3</span></td>
                            <td>3 / <span class="red">5</span></td>
                            <td>9 / <span class="red">10</span></td>
                        </tr>
                        <tr>
                            <td class="tddark">Store 6</td>
                            <td>1 / <span class="green">1</span></td>
                            <td>1 / <span class="green">1</span></td>
                            <td>5 / <span class="green">5</span></td>
                            <td>3 / <span class="green">3</span></td>
                            <td>3 / <span class="red">5</span></td>
                            <td>9 / <span class="red">10</span></td>
                        </tr>
                        <tr>
                            <td class="tddark">Store 7</td>
                            <td>1 / <span class="green">1</span></td>
                            <td>1 / <span class="green">1</span></td>
                            <td>5 / <span class="green">5</span></td>
                            <td>3 / <span class="green">3</span></td>
                            <td>3 / <span class="red">5</span></td>
                            <td>9 / <span class="red">10</span></td>
                        </tr>
                        <tr>
                            <td class="tddark">Store 8</td>
                            <td>1 / <span class="green">1</span></td>
                            <td>1 / <span class="green">1</span></td>
                            <td>5 / <span class="green">5</span></td>
                            <td>3 / <span class="green">3</span></td>
                            <td>3 / <span class="red">5</span></td>
                            <td>9 / <span class="red">10</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection