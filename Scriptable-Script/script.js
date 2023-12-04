// ENTER URL FROM THE FILE OF THE GITHUB REPO AS WIDGET PARAMETER
const url = args.widgetParameter || "https://example.com/getServerData.php";
const req = new Request(url);
req.method = 'POST';
req.body = `api_key=ENTER_API_KEY`;
const res = await req.loadJSON();

// Erstellen des Widgets
let widget = new ListWidget();

// Berechnen der durchschnittlichen CPU-Auslastung
let totalCpuLoad = 0;
let coreCount = 0;
Object.keys(res.cpuLoads).forEach(core => {
    totalCpuLoad += res.cpuLoads[core];
    coreCount++;
});
const averageCpuLoad = totalCpuLoad / coreCount;

// Totle
const WidgetTitle = widget.addText("Serverauslastung");
WidgetTitle.font = Font.mediumSystemFont(20);
widget.addSpacer(4);

// CPU
const cpuTitle = widget.addText("Gesamte CPU Auslastung");
cpuTitle.font = Font.mediumSystemFont(14);
widget.addSpacer(4);

// Fortschrittsbalken für CPU-Auslastung
addProgressBar(widget, averageCpuLoad / 100);

// RAM
widget.addSpacer(8);
const ramTitle = widget.addText("RAM Auslastung");
ramTitle.font = Font.mediumSystemFont(14);
const ramLabel = widget.addText(`Genutzt: ${res.ram.used}MB von ${res.ram.total}MB (${res.ram.usagePercent}%)`);
ramLabel.font = Font.systemFont(12);
addProgressBar(widget, res.ram.usagePercent / 100);

// HDD/SDD
widget.addSpacer(8);
const diskTitle = widget.addText("Festplattenauslastung");
diskTitle.font = Font.mediumSystemFont(14);
const diskLabel = widget.addText(`Genutzt: ${res.disk.used}GB von ${res.disk.total}GB (${res.disk.usagePercent}%)`);
diskLabel.font = Font.systemFont(12);
addProgressBar(widget, res.disk.usagePercent / 100);

// Network
widget.addSpacer(8);
const networkTitle = widget.addText("Netzwerkverkehr");
networkTitle.font = Font.mediumSystemFont(14);
const networkLabel = widget.addText(`RX: ${res.network.rx_rate.toFixed(2)}KB/s, TX: ${res.network.tx_rate.toFixed(2)}KB/s`);
networkLabel.font = Font.systemFont(12);

// Systeminfo
widget.addSpacer(8);
const systemInfoTitle = widget.addText("Systeminformationen");
systemInfoTitle.font = Font.mediumSystemFont(14);
const systemInfoLabel = widget.addText(`${res.systemInfo.os}`);
systemInfoLabel.font = Font.systemFont(12);

// Hilfsfunktion zum Hinzufügen von Fortschrittsbalken
function addProgressBar(widget, fraction) {
    const context = new DrawContext();
    context.size = new Size(200, 20);
    context.opaque = false;

    // Hintergrund des Balkens
    context.setFillColor(new Color("#e5e5e5"));
    const bgPath = new Path();
    bgPath.addRoundedRect(new Rect(0, 0, 200, 20), 10, 10);
    context.addPath(bgPath);
    context.fillPath();

    // Fortschrittsbalken
    context.setFillColor(new Color("#007aff"));
    const fillPath = new Path();
    fillPath.addRoundedRect(new Rect(0, 0, 200 * fraction, 20), 10, 10);
    context.addPath(fillPath);
    context.fillPath();

    const image = context.getImage();
    widget.addImage(image);
}

// Widget konfigurieren und anzeigen
Script.setWidget(widget);
Script.complete();
widget.presentLarge();
