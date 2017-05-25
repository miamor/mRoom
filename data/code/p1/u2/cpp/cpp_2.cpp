#include <algorithm> // for std::find
#include <iterator>
#include <iostream>
using namespace std;

int Ar[100][100];
int d4[100];
int dn4[100];

int main () {
	int T, p, n;
	cin>>T;
	for (int t = 0; t < T; t++) {
		cin>>n;
		int nd4 = 0;
		int ndn4 = 0;
		for (int i = 0; i < 2*n; i++) {
			cin>>Ar[t][i];
			p = Ar[t][i];
			if (p%4 == 0) {
				d4[nd4] = p;
				nd4++;
			} else {
				dn4[ndn4] = p;
				ndn4++;
			}
		}
		cout<<"Case #"<<(t+1)<<": ";
		for (int i = 0; i < nd4; i++) {
			int sales = d4[i]*3/4;
			
			int sP = 0;
		//	bool exists = std::find(std::begin(dn4), std::end(dn4), sales) != std::end(dn4);
		    int key = std::distance(std::begin(dn4), std::find(begin(dn4), std::end(dn4), sales));
		    if (key < ndn4) { // found 
		        sP = dn4[key];
				dn4[key] = -1;
		    } else { // not found
		        key = std::distance(std::begin(d4), std::find(begin(d4), std::end(d4), sales));
		        sP = d4[key];
				d4[key] = -1;
		    }
			if (sP > 0) cout<<sP<<" ";
		}
		cout<<"\n";
	}
	return 0;
}