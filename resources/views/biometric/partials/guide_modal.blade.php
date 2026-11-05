<!-- Modal: Supported Device Types & Integration Modes -->
<div class="modal fade" id="deviceSupportModal" tabindex="-1" role="dialog" aria-labelledby="deviceSupportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
            <div class="modal-header bg-dark text-white py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded p-2 mr-2.5 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-white" id="deviceSupportModalLabel">
                            Supported Device Types &amp; Integration Modes
                        </h5>
                        <small class="text-slate-300 opacity-75">Hardware compatibility, protocols, and architectural setup guidance</small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-75" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background: #f8fafc;">
                <!-- Overview Banner -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff;">
                    <div class="card-body p-3.5">
                        <h6 class="font-weight-bold text-cyan-400 mb-1.5"><i class="fas fa-network-wired mr-1"></i> How Devices Connect to School ERP</h6>
                        <p class="small text-slate-300 mb-0 leading-relaxed">
                            There are two primary ways to connect a fingerprint or facial recognition terminal:
                            <strong class="text-white">Cloud Push</strong> needs no local software and comes in two protocols: 
                            <strong class="text-cyan-300">ADMS (iclock)</strong> &mdash; the native two-way protocol for ZKTeco / eSSL / Realtime &mdash; and a 
                            <strong class="text-emerald-300">JSON Webhook</strong> for TimeWatch and generic HTTP devices.
                            <strong class="text-amber-300">Local Agent</strong> is for LAN-only devices that need a lightweight PC daemon on-site.
                        </p>
                    </div>
                </div>

                <div class="row">
                    <!-- Mode 1: Agent Sync -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-header bg-white border-bottom py-3">
                                <span class="badge badge-primary px-2.5 py-1 text-uppercase mr-1.5">Mode 1</span>
                                <strong class="text-dark">Agent Sync (Pull Mode)</strong>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">
                                    A lightweight <strong>Python / Node.js agent</strong> runs on a local PC/server on the same network as the device. It connects to the device via <strong>ZKTeco SDK (ZK Protocol)</strong>, pulls logs, and sends them to the cloud.
                                </p>
                                <h6 class="font-weight-bold text-dark font-size-13 mb-2"><i class="fas fa-check-circle text-success mr-1"></i> Capabilities</h6>
                                <ul class="small text-muted pl-3 mb-3">
                                    <li>Real-time attendance log pull &amp; cloud sync</li>
                                    <li>Remote commands (Reboot, Shutdown, Time Sync, Voice Test)</li>
                                    <li>User list sync &amp; template backup/restore directly from dashboard</li>
                                    <li>Clear device logs &amp; clear admin lockout</li>
                                </ul>

                                <h6 class="font-weight-bold text-dark font-size-13 mb-2"><i class="fas fa-microchip text-primary mr-1"></i> Supported Brands &amp; Models</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered small mb-0">
                                        <thead class="thead-light">
                                            <tr><th>Brand</th><th>Models</th><th>Protocol</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr><td><strong>ZKTeco</strong></td><td>K40, K50, F18, F22, uFace 302, SpeedFace V5L, MB460</td><td>ZK Protocol (TCP)</td></tr>
                                            <tr><td><strong>eSSL</strong></td><td>X990, eFace10, Identix series, SilkBio-101TC</td><td>ZK Protocol (TCP)</td></tr>
                                            <tr><td><strong>Realtime</strong></td><td>RS-405, T28, T304F, T502, C121-TA</td><td>ZK Protocol (TCP)</td></tr>
                                            <tr><td><strong>Anviz / CAMS</strong></td><td>EP300, C2 Pro, FaceDeep 3, SuperLog series</td><td>ZK Protocol (TCP)</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mode 2: Cloud Push -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-header bg-white border-bottom py-3">
                                <span class="badge badge-success px-2.5 py-1 text-uppercase mr-1.5">Mode 2</span>
                                <strong class="text-dark">Cloud Push (No Software)</strong>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">
                                    The device connects <strong>directly to the cloud server</strong> using its built-in Cloud Server / ADMS feature &mdash; zero local PC or agent required.
                                </p>
                                <h6 class="font-weight-bold text-dark font-size-13 mb-2"><i class="fas fa-check-circle text-success mr-1"></i> Capabilities</h6>
                                <ul class="small text-muted pl-3 mb-3">
                                    <li>Zero software footprint on campus (ideal for remote branches)</li>
                                    <li>Native ADMS two-way handshake (ATTLOG push, user template sync)</li>
                                    <li>Direct JSON webhooks for Hikvision, Dahua, TimeWatch, Mantra</li>
                                    <li>Automatic reconnection and buffered offline punches</li>
                                </ul>

                                <h6 class="font-weight-bold text-dark font-size-13 mb-2"><i class="fas fa-satellite-dish text-success mr-1"></i> Comparison Matrix</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered small mb-0">
                                        <thead class="thead-light">
                                            <tr><th>Feature</th><th>Local Agent</th><th>Cloud · ADMS</th><th>Cloud · JSON</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>No PC Needed</td><td><span class="text-danger">No</span></td><td><span class="text-success font-weight-bold">Yes</span></td><td><span class="text-success font-weight-bold">Yes</span></td></tr>
                                            <tr><td>Real-time Logs</td><td><span class="text-success font-weight-bold">Yes</span></td><td><span class="text-success font-weight-bold">Yes</span></td><td><span class="text-success font-weight-bold">Yes</span></td></tr>
                                            <tr><td>Remote Reboot</td><td><span class="text-success font-weight-bold">Yes</span></td><td><span class="text-warning">Optional</span></td><td><span class="text-danger">No</span></td></tr>
                                            <tr><td>Push Users</td><td><span class="text-success font-weight-bold">Yes</span></td><td><span class="text-success font-weight-bold">Yes</span></td><td><span class="text-warning">Device API</span></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guidance Footer -->
                <div class="alert alert-info border-0 mb-0 py-2.5" style="border-radius: 8px;">
                    <i class="fas fa-info-circle mr-1"></i> <strong>Recommendation:</strong> If your biometric machine has internet access (Wi-Fi or Ethernet), configure <strong>Cloud Push (ADMS)</strong> with Cloud Server IP set to your School ERP host on port 80/443. For isolated LAN machines without direct internet, install the <strong>Windows Agent</strong>.
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close Guide</button>
            </div>
        </div>
    </div>
</div>

